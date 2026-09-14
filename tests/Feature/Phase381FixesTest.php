<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase381FixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_job_catalog(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Should show jobs index view rather than default Laravel welcome page
        $response->assertViewIs('jobs.index');
        $response->assertSee('Temukan Pekerjaan Impianmu');
    }

    public function test_company_logo_url_accessor_handles_null_external_and_local(): void
    {
        $user = User::factory()->create(['role' => 'employer']);

        // 1. Logo is null
        $company1 = Company::create([
            'user_id' => $user->id,
            'name' => 'PT Null Logo',
            'location' => 'Jakarta',
            'logo' => null,
        ]);
        $this->assertNull($company1->logo_url);

        // 2. Logo is external URL
        $externalUrl = 'https://example.com/brand/logo.png';
        $company1->logo = $externalUrl;
        $company1->save();
        $this->assertEquals($externalUrl, $company1->fresh()->logo_url);

        // 3. Logo is local storage path
        $localPath = 'companies/logos/logo123.png';
        $company1->logo = $localPath;
        $company1->save();
        $this->assertEquals(asset('storage/' . $localPath), $company1->fresh()->logo_url);
    }

    public function test_admin_navigation_contains_moderation_menu(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Moderasi Lowongan');
        $response->assertSee(route('admin.job-postings.index'));
    }

    public function test_non_admin_does_not_see_moderation_menu(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);
        Company::create([
            'user_id' => $employer->id,
            'name' => 'PT Non Admin',
            'location' => 'Jakarta',
        ]);

        $response = $this->actingAs($employer)->get(route('employer.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Moderasi Lowongan');
    }

    public function test_employer_cannot_create_duplicate_company_via_controller_guard(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        Company::create([
            'user_id' => $employer->id,
            'name' => 'First Company',
            'location' => 'Jakarta',
        ]);

        $response = $this->actingAs($employer)->post(route('company.store'), [
            'name' => 'Second Company Attempt',
            'location' => 'Surabaya',
        ]);

        $response->assertRedirect(route('company.show'));
        $response->assertSessionHas('error', 'Anda sudah memiliki profil perusahaan.');
        $this->assertEquals(1, Company::where('user_id', $employer->id)->count());
    }

    public function test_database_enforces_unique_user_id_on_companies(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        Company::create([
            'user_id' => $employer->id,
            'name' => 'Company A',
            'location' => 'Jakarta',
        ]);

        $this->expectException(QueryException::class);

        // Directly attempt duplicate user_id insertion into companies table
        Company::create([
            'user_id' => $employer->id,
            'name' => 'Company B',
            'location' => 'Bandung',
        ]);
    }

    public function test_company_description_supports_text_longer_than_255_chars(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $longDescription = trim(str_repeat('Perusahaan terdepan dalam bidang teknologi dan inovasi digital. ', 20)); // ~1300 chars

        $response = $this->actingAs($employer)->post(route('company.store'), [
            'name' => 'PT Teknologi Jaya',
            'location' => 'Jakarta',
            'description' => $longDescription,
        ]);

        $response->assertRedirect(route('company.show'));
        $this->assertDatabaseHas('companies', [
            'user_id' => $employer->id,
            'name' => 'PT Teknologi Jaya',
            'description' => $longDescription,
        ]);
    }

    public function test_company_show_page_displays_dynamic_jobs_and_count(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $employer->id,
            'name' => 'PT Mega Corp',
            'location' => 'Jakarta',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Software Engineer Backend',
            'description' => 'Membangun API berskala besar.',
            'location' => 'Jakarta Selatan',
            'employment_type' => 'Full-time',
            'salary_min' => 12000000,
            'salary_max' => 18000000,
            'status' => 'active',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'UI/UX Product Designer',
            'description' => 'Mendesain antarmuka pengguna.',
            'location' => 'Jakarta Pusat',
            'employment_type' => 'Full-time',
            'salary_min' => 10000000,
            'salary_max' => 15000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employer)->get(route('company.show'));

        $response->assertStatus(200);
        $response->assertSee('Software Engineer Backend');
        $response->assertSee('UI/UX Product Designer');
        $response->assertSee('2 Lowongan');
    }
}
