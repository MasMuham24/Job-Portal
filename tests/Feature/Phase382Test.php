<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase382Test extends TestCase
{
    use RefreshDatabase;

    private function createEmployerWithCompany(string $companyName = 'PT Inovasi'): array
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => $companyName,
            'location' => 'Jakarta',
        ]);

        return [$user, $company];
    }

    // TASK 1: JOB EDIT MODERATION
    public function test_editing_active_job_resets_status_to_pending(): void
    {
        [$employer, $company] = $this->createEmployerWithCompany();

        $job = JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Golang Developer',
            'description' => 'Membangun microservices.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employer)->put(route('job-postings.update', $job), [
            'title' => 'Senior Golang Developer',
            'description' => 'Membangun microservices skala enterprise.',
            'location' => 'Jakarta Selatan',
            'employment_type' => 'Full-time',
        ]);

        $response->assertRedirect(route('job-postings.show', $job));
        $this->assertEquals('pending', $job->fresh()->status);
        $this->assertEquals('Senior Golang Developer', $job->fresh()->title);
    }

    public function test_editing_pending_job_retains_pending_status(): void
    {
        [$employer, $company] = $this->createEmployerWithCompany();

        $job = JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Junior Developer',
            'description' => 'Membantu tim backend.',
            'location' => 'Bandung',
            'employment_type' => 'Internship',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employer)->put(route('job-postings.update', $job), [
            'title' => 'Junior Backend Developer',
            'description' => 'Membantu tim backend Laravel.',
            'location' => 'Bandung',
            'employment_type' => 'Internship',
        ]);

        $response->assertRedirect(route('job-postings.show', $job));
        $this->assertEquals('pending', $job->fresh()->status);
        $this->assertEquals('Junior Backend Developer', $job->fresh()->title);
    }

    public function test_employer_cannot_edit_another_employers_job(): void
    {
        [$employer1, $company1] = $this->createEmployerWithCompany('PT Alpha');
        [$employer2, $company2] = $this->createEmployerWithCompany('PT Beta');

        $job = JobPosting::create([
            'company_id' => $company1->id,
            'title' => 'QA Engineer',
            'description' => 'Testing aplikasi.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employer2)->put(route('job-postings.update', $job), [
            'title' => 'Hacked Title',
            'description' => 'Testing aplikasi hacked.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('QA Engineer', $job->fresh()->title);
        $this->assertEquals('active', $job->fresh()->status);
    }

    // TASK 2: COMPANY UI LANGUAGE
    public function test_company_create_and_edit_views_use_indonesian(): void
    {
        $newEmployer = User::factory()->create(['role' => 'employer']);

        $responseCreate = $this->actingAs($newEmployer)->get(route('company.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Buat Profil Perusahaan');
        $responseCreate->assertSee('Simpan Profil Perusahaan');
        $responseCreate->assertDontSee('Create your company profile');
        $responseCreate->assertDontSee('Create Company');

        $company = Company::create([
            'user_id' => $newEmployer->id,
            'name' => 'PT Bahasa Indonesia',
            'location' => 'Jakarta',
        ]);

        $newEmployer->refresh();
        $responseEdit = $this->actingAs($newEmployer)->get(route('company.edit'));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Profil Perusahaan');
        $responseEdit->assertSee('Perbarui informasi perusahaan Anda.');
        $responseEdit->assertSee('Simpan Perubahan');
        $responseEdit->assertDontSee('Edit company profile');
        $responseEdit->assertDontSee('Save Changes');
    }

    // TASK 3: EMPLOYER DASHBOARD METRICS & OWNERSHIP FILTERING
    public function test_employer_dashboard_metrics_counts_only_own_applications(): void
    {
        [$employerA, $companyA] = $this->createEmployerWithCompany('PT Sukses A');
        [$employerB, $companyB] = $this->createEmployerWithCompany('PT Sukses B');

        $jobA1 = JobPosting::create([
            'company_id' => $companyA->id,
            'title' => 'Job A1',
            'description' => 'Desc A1',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $jobA2 = JobPosting::create([
            'company_id' => $companyA->id,
            'title' => 'Job A2',
            'description' => 'Desc A2',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $jobB = JobPosting::create([
            'company_id' => $companyB->id,
            'title' => 'Job B1',
            'description' => 'Desc B1',
            'location' => 'Surabaya',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $seeker1 = User::factory()->create(['role' => 'job_seeker']);
        $seeker2 = User::factory()->create(['role' => 'job_seeker']);

        // 2 applications for Company A (1 pending, 1 accepted)
        Application::create([
            'user_id' => $seeker1->id,
            'job_posting_id' => $jobA1->id,
            'status' => 'pending',
        ]);
        Application::create([
            'user_id' => $seeker2->id,
            'job_posting_id' => $jobA2->id,
            'status' => 'accepted',
        ]);

        // 1 application for Company B (pending)
        Application::create([
            'user_id' => $seeker1->id,
            'job_posting_id' => $jobB->id,
            'status' => 'pending',
        ]);

        // When Employer A visits dashboard
        $responseA = $this->actingAs($employerA)->get(route('employer.dashboard'));
        $responseA->assertStatus(200);
        $responseA->assertViewHas('totalApplications', 2);
        $responseA->assertViewHas('pendingApplications', 1);
        $responseA->assertSee('Total Lamaran');
        $responseA->assertSee('Menunggu Review');

        // When Employer B visits dashboard
        $responseB = $this->actingAs($employerB)->get(route('employer.dashboard'));
        $responseB->assertStatus(200);
        $responseB->assertViewHas('totalApplications', 1);
        $responseB->assertViewHas('pendingApplications', 1);
    }

    // TASK 5: CUSTOM ERROR PAGES
    public function test_custom_404_error_page_renders_indonesian_content(): void
    {
        $response = $this->get('/non-existent-random-url-page-xyz');

        $response->assertStatus(404);
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('Halaman Utama');
    }

    public function test_custom_403_error_page_renders_indonesian_content(): void
    {
        [$employer1, $company1] = $this->createEmployerWithCompany('PT Satu');
        [$employer2, $company2] = $this->createEmployerWithCompany('PT Dua');

        $job = JobPosting::create([
            'company_id' => $company1->id,
            'title' => 'Secured Job',
            'description' => 'Secret',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        // Employer 2 attempting to delete employer 1's job causes 403
        $response = $this->actingAs($employer2)->delete(route('job-postings.destroy', $job));

        $response->assertStatus(403);
        $response->assertSee('Akses Ditolak');
        $response->assertSee('Halaman Utama');
    }
}
