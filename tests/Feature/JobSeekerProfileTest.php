<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createCompleteJobSeeker(): User
    {
        return User::factory()->create([
            'role' => 'job_seeker',
            'phone' => '08123456789',
            'gender' => 'Laki-laki',
            'birth_date' => '1998-05-15',
            'address' => 'Jl. Kebon Jeruk No. 12',
            'city' => 'Jakarta Barat',
            'education' => 'Sarjana (S1)',
            'school' => 'Universitas Indonesia',
            'skills' => 'PHP, Laravel, Vue.js',
            'experience' => '2 tahun sebagai Junior Developer',
            'bio' => 'Saya seorang web developer yang bersemangat dalam membangun solusi digital.',
        ]);
    }

    private function createIncompleteJobSeeker(): User
    {
        return User::factory()->create([
            'role' => 'job_seeker',
            // Profile fields are null by default
        ]);
    }

    private function createActiveJob(): array
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $employer->id,
            'name' => 'PT Solusi Teknologi',
            'location' => 'Jakarta',
        ]);
        $job = $company->jobs()->create([
            'title' => 'Backend Developer',
            'description' => 'Membangun API menggunakan Laravel.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        return [$employer, $company, $job];
    }

    // ==========================================
    // 1. DASHBOARD TESTS
    // ==========================================

    public function test_job_seeker_can_view_dashboard_with_real_metrics(): void
    {
        $seeker = $this->createCompleteJobSeeker();
        [, , $job1] = $this->createActiveJob();
        [, , $job2] = $this->createActiveJob();

        Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job1->id,
            'status' => Application::STATUS_PENDING,
        ]);

        Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job2->id,
            'status' => Application::STATUS_ACCEPTED,
        ]);

        $response = $this->actingAs($seeker)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee('job_seeker');
        $response->assertSee('100%');
        $response->assertSee('Lengkap');
        $response->assertSee('Total Lamaran');
        $response->assertSee($job1->title);
    }

    public function test_dashboard_shows_incomplete_status_for_new_job_seeker(): void
    {
        $seeker = $this->createIncompleteJobSeeker();

        $response = $this->actingAs($seeker)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee('Belum Lengkap');
        $response->assertSee('Lengkapi Profil Sekarang');
    }

    // ==========================================
    // 2. PROFILE COMPLETION CALCULATION TESTS
    // ==========================================

    public function test_profile_completion_percentage_calculation(): void
    {
        $seeker = User::factory()->create([
            'role' => 'job_seeker',
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            // 2 out of 11 required fields filled = ~18%
        ]);

        $this->assertFalse($seeker->isProfileComplete());
        $this->assertEquals(18, $seeker->profileCompletionPercentage());

        // Fill all required fields
        $seeker->update([
            'phone' => '0811223344',
            'gender' => 'Laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Merdeka No. 5',
            'city' => 'Bandung',
            'education' => 'Sarjana (S1)',
            'school' => 'Institut Teknologi Bandung',
            'skills' => 'Python, Django',
            'bio' => 'Data scientist antusias.',
        ]);

        $this->assertTrue($seeker->fresh()->isProfileComplete());
        $this->assertEquals(100, $seeker->fresh()->profileCompletionPercentage());
    }

    public function test_fresh_graduate_with_empty_experience_still_has_complete_profile(): void
    {
        $seeker = User::factory()->create([
            'role' => 'job_seeker',
            'name' => 'Fresh Graduate',
            'email' => 'fresh@example.com',
            'phone' => '0812345678',
            'gender' => 'Perempuan',
            'birth_date' => '2002-01-01',
            'address' => 'Jl. Melati No. 1',
            'city' => 'Yogyakarta',
            'education' => 'Sarjana (S1)',
            'school' => 'UGM',
            'skills' => 'Public Speaking, Writing',
            'experience' => null, // Optional for fresh graduates!
            'bio' => 'Lulusan baru berprestasi siap berkarir.',
        ]);

        $this->assertTrue($seeker->isProfileComplete());
        $this->assertEquals(100, $seeker->profileCompletionPercentage());
    }

    // ==========================================
    // 3. EDIT PROFILE TESTS
    // ==========================================

    public function test_job_seeker_can_view_profile_edit_screen(): void
    {
        $seeker = $this->createIncompleteJobSeeker();

        $response = $this->actingAs($seeker)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee('Informasi Pribadi');
    }

    public function test_job_seeker_can_update_own_profile(): void
    {
        $seeker = $this->createIncompleteJobSeeker();

        $response = $this->actingAs($seeker)->put(route('profile.update'), [
            'name' => 'Budi Santoso Diperbarui',
            'phone' => '081299998888',
            'gender' => 'Laki-laki',
            'birth_date' => '1996-03-20',
            'address' => 'Jl. Baru No. 99',
            'city' => 'Surabaya',
            'education' => 'Sarjana (S1)',
            'school' => 'ITS Surabaya',
            'skills' => 'Go, Docker, Kubernetes',
            'experience' => 'Pernah magang 6 bulan di startup',
            'bio' => 'Backend engineer spesialis Go.',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $fresh = $seeker->fresh();
        $this->assertEquals('Budi Santoso Diperbarui', $fresh->name);
        $this->assertEquals('081299998888', $fresh->phone);
        $this->assertEquals('Surabaya', $fresh->city);
        $this->assertTrue($fresh->isProfileComplete());
    }

    public function test_job_seeker_profile_validation_rules(): void
    {
        $seeker = $this->createIncompleteJobSeeker();

        $response = $this->actingAs($seeker)->put(route('profile.update'), [
            'name' => '', // required
            'phone' => '', // required
            'gender' => 'InvalidGender', // in:Laki-laki,Perempuan
        ]);

        $response->assertSessionHasErrors(['name', 'phone', 'gender', 'birth_date', 'address', 'city', 'education', 'school', 'skills', 'bio']);
    }

    // ==========================================
    // 4. APPLICATION GATING TESTS
    // ==========================================

    public function test_job_seeker_with_incomplete_profile_cannot_apply(): void
    {
        $seeker = $this->createIncompleteJobSeeker();
        [, , $job] = $this->createActiveJob();

        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Saya ingin melamar',
        ]);

        // Should be rejected by backend and redirected to profile.edit
        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('error', 'Lengkapi profil Anda terlebih dahulu sebelum melamar pekerjaan.');

        $this->assertDatabaseMissing('applications', [
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
        ]);
    }

    public function test_job_seeker_with_complete_profile_can_apply(): void
    {
        $seeker = $this->createCompleteJobSeeker();
        [, , $job] = $this->createActiveJob();

        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Saya memenuhi seluruh kualifikasi.',
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);
    }

    public function test_job_detail_ui_shows_complete_profile_warning_when_incomplete(): void
    {
        $seeker = $this->createIncompleteJobSeeker();
        [, , $job] = $this->createActiveJob();

        $response = $this->actingAs($seeker)->get(route('jobs.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Profil Belum Lengkap');
        $response->assertSee('Lengkapi Profil');
        $response->assertDontSee('Lamar Sekarang');
    }

    public function test_job_detail_ui_shows_apply_button_when_complete(): void
    {
        $seeker = $this->createCompleteJobSeeker();
        [, , $job] = $this->createActiveJob();

        $response = $this->actingAs($seeker)->get(route('jobs.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Lamar Sekarang');
    }

    // ==========================================
    // 5. EMPLOYER CAN VIEW APPLICANT PROFILE
    // ==========================================

    public function test_employer_can_view_applicant_profile_data(): void
    {
        $seeker = $this->createCompleteJobSeeker();
        [$employer, , $job] = $this->createActiveJob();

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employer)->get(route('employer.applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee($seeker->phone);
        $response->assertSee($seeker->city);
        $response->assertSee($seeker->school);
        $response->assertSee('Profil Lengkap Kandidat');
    }

    // ==========================================
    // 6. AUTHORIZATION TESTS
    // ==========================================

    public function test_guest_cannot_access_profile_routes(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'))->assertRedirect(route('login'));
    }

    public function test_employer_cannot_access_job_seeker_profile_routes(): void
    {
        [$employer] = $this->createActiveJob();

        $this->actingAs($employer)->get(route('profile.edit'))->assertStatus(403);
    }
}
