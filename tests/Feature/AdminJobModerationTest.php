<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobModerationTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createEmployerWithCompany(): User
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::create([
            'user_id' => $user->id,
            'name' => 'PT Test Indonesia',
            'location' => 'Jakarta',
        ]);
        return $user;
    }

    private function createJobPosting(Company $company, array $overrides = []): JobPosting
    {
        return $company->jobs()->create(array_merge([
            'title' => 'Frontend Developer',
            'description' => 'Mengembangkan aplikasi web menggunakan React.',
            'location' => 'Jakarta Selatan',
            'employment_type' => 'Full-time',
            'salary_min' => 5000000,
            'salary_max' => 10000000,
            'status' => 'pending',
        ], $overrides));
    }

    public function test_admin_can_access_job_posting_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.job-postings.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Lowongan Kerja');
    }

    public function test_admin_can_view_job_detail(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $response = $this->actingAs($admin)->get(route('admin.job-postings.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Frontend Developer');
        $response->assertSee('PT Test Indonesia');
    }

    public function test_admin_can_approve_pending_job(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company, ['status' => 'pending']);

        $response = $this->actingAs($admin)->put(route('admin.job-postings.status', $job), [
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.job-postings.show', $job));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_postings', [
            'id' => $job->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_mark_active_job_as_pending(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company, ['status' => 'active']);

        $response = $this->actingAs($admin)->put(route('admin.job-postings.status', $job), [
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('admin.job-postings.show', $job));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_postings', [
            'id' => $job->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_cannot_set_invalid_status(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $response = $this->actingAs($admin)->put(route('admin.job-postings.status', $job), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('job_postings', [
            'id' => $job->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_delete_job(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $response = $this->actingAs($admin)->delete(route('admin.job-postings.destroy', $job));

        $response->assertRedirect(route('admin.job-postings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('job_postings', ['id' => $job->id]);
    }

    public function test_employer_cannot_access_admin_job_postings(): void
    {
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $this->actingAs($employer)->get(route('admin.job-postings.index'))->assertStatus(403);
        $this->actingAs($employer)->get(route('admin.job-postings.show', $job))->assertStatus(403);
        $this->actingAs($employer)->put(route('admin.job-postings.status', $job), ['status' => 'active'])->assertStatus(403);
        $this->actingAs($employer)->delete(route('admin.job-postings.destroy', $job))->assertStatus(403);
    }

    public function test_job_seeker_cannot_access_admin_job_postings(): void
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $this->actingAs($seeker)->get(route('admin.job-postings.index'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('admin.job-postings.show', $job))->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_admin_job_postings(): void
    {
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $this->get(route('admin.job-postings.index'))->assertRedirect('/login');
        $this->get(route('admin.job-postings.show', $job))->assertRedirect('/login');
    }

    public function test_admin_can_filter_jobs_by_status(): void
    {
        $admin = $this->createAdmin();
        $employer = $this->createEmployerWithCompany();
        $pendingJob = $this->createJobPosting($employer->company, ['title' => 'Pending Job', 'status' => 'pending']);
        $activeJob = $this->createJobPosting($employer->company, ['title' => 'Active Job', 'status' => 'active']);

        $response = $this->actingAs($admin)->get(route('admin.job-postings.index', ['status' => 'pending']));
        $response->assertSee('Pending Job');
        $response->assertDontSee('Active Job');

        $response = $this->actingAs($admin)->get(route('admin.job-postings.index', ['status' => 'active']));
        $response->assertSee('Active Job');
        $response->assertDontSee('Pending Job');
    }

    public function test_job_creates_with_pending_status(): void
    {
        $employer = $this->createEmployerWithCompany();

        $this->actingAs($employer)->post(route('job-postings.store'), [
            'title' => 'New Job',
            'description' => 'Test description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
        ]);

        $this->assertDatabaseHas('job_postings', [
            'title' => 'New Job',
            'status' => 'pending',
        ]);
    }

    public function test_admin_dashboard_quick_actions_link(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.job-postings.index'));
    }

    public function test_admin_dashboard_still_works(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }
}
