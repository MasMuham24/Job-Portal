<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobStatusTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveJob(): JobPosting
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'PT Test Indonesia',
            'location' => 'Jakarta',
        ]);

        return $company->jobs()->create([
            'title' => 'Frontend Developer',
            'description' => 'Mengembangkan aplikasi web.',
            'location' => 'Jakarta Selatan',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);
    }

    public function test_guest_can_access_job_statuses_endpoint(): void
    {
        $job = $this->createActiveJob();

        $response = $this->getJson(route('api.job-postings.statuses'));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'id' => $job->id,
            'status' => 'active',
        ]);
    }

    public function test_job_statuses_returns_only_active_jobs(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'PT Test',
            'location' => 'Jakarta',
        ]);

        $activeJob = $company->jobs()->create([
            'title' => 'Active Job',
            'description' => 'Description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $pendingJob = $company->jobs()->create([
            'title' => 'Pending Job',
            'description' => 'Description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'pending',
        ]);

        $closedJob = $company->jobs()->create([
            'title' => 'Closed Job',
            'description' => 'Description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'closed',
        ]);

        $response = $this->getJson(route('api.job-postings.statuses'));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $activeJob->id]);
        $response->assertJsonMissing(['id' => $pendingJob->id]);
        $response->assertJsonMissing(['id' => $closedJob->id]);
    }

    public function test_job_statuses_returns_lightweight_data(): void
    {
        $job = $this->createActiveJob();

        $response = $this->getJson(route('api.job-postings.statuses'));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertArrayHasKey('updated_at', $data[0]);
        $this->assertArrayNotHasKey('title', $data[0]);
        $this->assertArrayNotHasKey('description', $data[0]);
    }

    public function test_single_job_status_endpoint_returns_correct_data(): void
    {
        $job = $this->createActiveJob();

        $response = $this->getJson(route('api.job-postings.status', $job));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $job->id,
            'status' => 'active',
        ]);
    }

    public function test_single_job_status_returns_lightweight_data(): void
    {
        $job = $this->createActiveJob();

        $response = $this->getJson(route('api.job-postings.status', $job));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertArrayNotHasKey('title', $data);
        $this->assertArrayNotHasKey('description', $data);
    }

    public function test_single_job_status_shows_pending_status(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'PT Test',
            'location' => 'Jakarta',
        ]);

        $job = $company->jobs()->create([
            'title' => 'Pending Job',
            'description' => 'Description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'pending',
        ]);

        $response = $this->getJson(route('api.job-postings.status', $job));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $job->id,
            'status' => 'pending',
        ]);
    }

    public function test_status_change_is_detected(): void
    {
        $job = $this->createActiveJob();

        // Initial status
        $response = $this->getJson(route('api.job-postings.status', $job));
        $response->assertJson(['status' => 'active']);

        // Change status to pending
        $job->update(['status' => 'pending']);

        // Should reflect new status
        $response = $this->getJson(route('api.job-postings.status', $job));
        $response->assertJson(['status' => 'pending']);
    }

    public function test_multiple_jobs_statuses(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'PT Test',
            'location' => 'Jakarta',
        ]);

        $job1 = $company->jobs()->create([
            'title' => 'Job 1',
            'description' => 'Description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'status' => 'active',
        ]);

        $job2 = $company->jobs()->create([
            'title' => 'Job 2',
            'description' => 'Description.',
            'location' => 'Bandung',
            'employment_type' => 'Part-time',
            'status' => 'active',
        ]);

        $response = $this->getJson(route('api.job-postings.statuses'));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}
