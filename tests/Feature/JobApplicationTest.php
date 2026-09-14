<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function createJobSeeker(): User
    {
        return User::factory()->create([
            'role' => 'job_seeker',
        ]);
    }

    private function createEmployerWithJob(string $jobStatus = 'active'): array
    {
        $employer = User::factory()->create([
            'role' => 'employer',
        ]);

        $company = Company::create([
            'user_id' => $employer->id,
            'name' => 'PT Perusahaan Sukses',
            'location' => 'Jakarta',
        ]);

        $job = $company->jobs()->create([
            'title' => 'Software Engineer',
            'description' => 'Membangun aplikasi skala besar.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => $jobStatus,
        ]);

        return [$employer, $company, $job];
    }

    // ==========================================
    // 1. JOB SEEKER TESTS
    // ==========================================

    public function test_job_seeker_can_apply_to_active_job(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Saya tertarik dengan lowongan ini dan memiliki pengalaman 3 tahun.',
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
            'cover_letter' => 'Saya tertarik dengan lowongan ini dan memiliki pengalaman 3 tahun.',
        ]);
    }

    public function test_application_created_with_default_pending_status(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $this->actingAs($seeker)->post(route('jobs.apply', $job));

        $application = Application::where('user_id', $seeker->id)
            ->where('job_posting_id', $job->id)
            ->first();

        $this->assertNotNull($application);
        $this->assertEquals(Application::STATUS_PENDING, $application->status);
    }

    public function test_job_seeker_cannot_apply_to_pending_job(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('pending');

        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Test apply',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('applications', [
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
        ]);
    }

    public function test_job_seeker_cannot_apply_to_closed_job(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('closed');

        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Test apply',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('applications', [
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
        ]);
    }

    public function test_duplicate_application_is_rejected(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        // First application
        $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Lamaran pertama',
        ]);

        $this->assertEquals(1, Application::where('user_id', $seeker->id)->where('job_posting_id', $job->id)->count());

        // Second application attempt
        $response = $this->actingAs($seeker)->post(route('jobs.apply', $job), [
            'cover_letter' => 'Lamaran kedua',
        ]);

        $response->assertSessionHas('error', 'Anda sudah melamar lowongan ini.');
        $this->assertEquals(1, Application::where('user_id', $seeker->id)->where('job_posting_id', $job->id)->count());
    }

    public function test_job_seeker_can_view_own_applications(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($seeker)->get(route('applications.index'));

        $response->assertStatus(200);
        $response->assertSee($job->title);
    }

    public function test_job_seeker_can_view_own_application_detail(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
            'cover_letter' => 'Pesan pengantar saya',
        ]);

        $response = $this->actingAs($seeker)->get(route('applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee('Pesan pengantar saya');
        $response->assertSee($job->title);
    }

    public function test_job_seeker_cannot_view_another_users_application(): void
    {
        $seeker1 = $this->createJobSeeker();
        $seeker2 = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker1->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($seeker2)->get(route('applications.show', $application));

        $response->assertStatus(403);
    }

    public function test_job_seeker_cannot_change_application_status(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($seeker)->put(route('employer.applications.status', $application), [
            'status' => Application::STATUS_ACCEPTED,
        ]);

        $response->assertStatus(403);
        $this->assertEquals(Application::STATUS_PENDING, $application->fresh()->status);
    }

    // ==========================================
    // 2. EMPLOYER TESTS
    // ==========================================

    public function test_employer_can_view_applications_for_own_jobs(): void
    {
        [$employer, , $job] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employer)->get(route('employer.applications.index'));

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee($job->title);
    }

    public function test_employer_can_view_application_detail(): void
    {
        [$employer, , $job] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
            'cover_letter' => 'Surat lamaran dari pelamar',
        ]);

        $response = $this->actingAs($employer)->get(route('employer.applications.show', $application));

        $response->assertStatus(200);
        $response->assertSee($seeker->name);
        $response->assertSee('Surat lamaran dari pelamar');
    }

    public function test_employer_can_update_application_status_for_own_job(): void
    {
        [$employer, , $job] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employer)->put(route('employer.applications.status', $application), [
            'status' => Application::STATUS_ACCEPTED,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(Application::STATUS_ACCEPTED, $application->fresh()->status);
    }

    public function test_employer_cannot_view_application_from_another_employers_job(): void
    {
        [$employer1] = $this->createEmployerWithJob('active');
        [, , $job2] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        $application2 = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job2->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employer1)->get(route('employer.applications.show', $application2));

        $response->assertStatus(403);
    }

    public function test_employer_cannot_update_application_from_another_employers_job(): void
    {
        [$employer1] = $this->createEmployerWithJob('active');
        [, , $job2] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        $application2 = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job2->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employer1)->put(route('employer.applications.status', $application2), [
            'status' => Application::STATUS_REJECTED,
        ]);

        $response->assertStatus(403);
        $this->assertEquals(Application::STATUS_PENDING, $application2->fresh()->status);
    }

    // ==========================================
    // 3. AUTHORIZATION TESTS
    // ==========================================

    public function test_guest_blocked_from_applications(): void
    {
        [, , $job] = $this->createEmployerWithJob('active');

        $this->get(route('applications.index'))->assertRedirect(route('login'));
        $this->post(route('jobs.apply', $job))->assertRedirect(route('login'));
        $this->get(route('employer.applications.index'))->assertRedirect(route('login'));
    }

    public function test_job_seeker_blocked_from_employer_application_management(): void
    {
        $seeker = $this->createJobSeeker();

        $this->actingAs($seeker)->get(route('employer.applications.index'))->assertStatus(403);
    }

    public function test_employer_blocked_from_job_seeker_application_management(): void
    {
        [$employer] = $this->createEmployerWithJob('active');

        $this->actingAs($employer)->get(route('applications.index'))->assertStatus(403);
    }

    // ==========================================
    // 4. AJAX POLLING ENDPOINT TESTS
    // ==========================================

    public function test_applicant_can_poll_application_status(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($seeker)->getJson(route('api.applications.status', $application));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $application->id,
            'status' => Application::STATUS_PENDING,
        ]);
    }

    public function test_employer_can_poll_application_status_for_own_job(): void
    {
        [$employer, , $job] = $this->createEmployerWithJob('active');
        $seeker = $this->createJobSeeker();

        $application = Application::create([
            'user_id' => $seeker->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_REVIEWED,
        ]);

        $response = $this->actingAs($employer)->getJson(route('api.applications.status', $application));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $application->id,
            'status' => Application::STATUS_REVIEWED,
        ]);
    }

    public function test_unauthorized_user_cannot_poll_application_status(): void
    {
        $seeker1 = $this->createJobSeeker();
        $seeker2 = $this->createJobSeeker();
        [, , $job] = $this->createEmployerWithJob('active');

        $application = Application::create([
            'user_id' => $seeker1->id,
            'job_posting_id' => $job->id,
            'status' => Application::STATUS_PENDING,
        ]);

        $response = $this->actingAs($seeker2)->getJson(route('api.applications.status', $application));

        $response->assertStatus(403);
    }

    public function test_job_seeker_can_poll_my_statuses(): void
    {
        $seeker = $this->createJobSeeker();
        [, , $job1] = $this->createEmployerWithJob('active');
        [, , $job2] = $this->createEmployerWithJob('active');

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

        $response = $this->actingAs($seeker)->getJson(route('api.applications.my-statuses'));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}
