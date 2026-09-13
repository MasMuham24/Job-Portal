<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobPostingTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_employer_can_access_job_posting_index(): void
    {
        $user = $this->createEmployerWithCompany();

        $response = $this->actingAs($user)->get(route('job-postings.index'));

        $response->assertStatus(200);
        $response->assertSee('Lowongan Kerja');
    }

    public function test_employer_can_access_create_form(): void
    {
        $user = $this->createEmployerWithCompany();

        $response = $this->actingAs($user)->get(route('job-postings.create'));

        $response->assertStatus(200);
        $response->assertSee('Buat Lowongan Baru');
    }

    public function test_employer_can_create_a_job(): void
    {
        $user = $this->createEmployerWithCompany();

        $response = $this->actingAs($user)->post(route('job-postings.store'), [
            'title' => 'Backend Developer',
            'description' => 'Mengembangkan API menggunakan Laravel.',
            'location' => 'Bandung',
            'employment_type' => 'Full-time',
            'salary_min' => 7000000,
            'salary_max' => 12000000,
        ]);

        $response->assertRedirect(route('job-postings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_postings', [
            'title' => 'Backend Developer',
            'location' => 'Bandung',
            'status' => 'pending',
        ]);
    }

    public function test_employer_can_view_own_job(): void
    {
        $user = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($user->company);

        $response = $this->actingAs($user)->get(route('job-postings.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Frontend Developer');
    }

    public function test_employer_can_edit_own_job(): void
    {
        $user = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($user->company);

        $response = $this->actingAs($user)->get(route('job-postings.edit', $job));

        $response->assertStatus(200);
        $response->assertSee('Edit Lowongan');
    }

    public function test_employer_can_update_own_job(): void
    {
        $user = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($user->company);

        $response = $this->actingAs($user)->put(route('job-postings.update', $job), [
            'title' => 'Senior Frontend Developer',
            'description' => 'Updated description for the role.',
            'location' => 'Surabaya',
            'employment_type' => 'Contract',
        ]);

        $response->assertRedirect(route('job-postings.show', $job));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_postings', [
            'id' => $job->id,
            'title' => 'Senior Frontend Developer',
            'location' => 'Surabaya',
        ]);
    }

    public function test_employer_can_delete_own_job(): void
    {
        $user = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($user->company);

        $response = $this->actingAs($user)->delete(route('job-postings.destroy', $job));

        $response->assertRedirect(route('job-postings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('job_postings', ['id' => $job->id]);
    }

    public function test_employer_cannot_edit_another_employers_job(): void
    {
        $employer1 = $this->createEmployerWithCompany();
        $employer2 = User::factory()->create(['role' => 'employer']);
        Company::create(['user_id' => $employer2->id, 'name' => 'PT Lain', 'location' => 'Bandung']);
        $otherJob = $this->createJobPosting($employer2->company);

        $response = $this->actingAs($employer1)->get(route('job-postings.edit', $otherJob));

        $response->assertStatus(403);
    }

    public function test_employer_cannot_update_another_employers_job(): void
    {
        $employer1 = $this->createEmployerWithCompany();
        $employer2 = User::factory()->create(['role' => 'employer']);
        Company::create(['user_id' => $employer2->id, 'name' => 'PT Lain', 'location' => 'Bandung']);
        $otherJob = $this->createJobPosting($employer2->company);

        $response = $this->actingAs($employer1)->put(route('job-postings.update', $otherJob), [
            'title' => 'Hacked Job',
            'description' => 'Unauthorized update.',
            'location' => 'Hack City',
            'employment_type' => 'Full-time',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('job_postings', [
            'id' => $otherJob->id,
            'title' => 'Frontend Developer',
        ]);
    }

    public function test_employer_cannot_delete_another_employers_job(): void
    {
        $employer1 = $this->createEmployerWithCompany();
        $employer2 = User::factory()->create(['role' => 'employer']);
        Company::create(['user_id' => $employer2->id, 'name' => 'PT Lain', 'location' => 'Bandung']);
        $otherJob = $this->createJobPosting($employer2->company);

        $response = $this->actingAs($employer1)->delete(route('job-postings.destroy', $otherJob));

        $response->assertStatus(403);

        $this->assertDatabaseHas('job_postings', ['id' => $otherJob->id]);
    }

    public function test_employer_cannot_view_another_employers_job(): void
    {
        $employer1 = $this->createEmployerWithCompany();
        $employer2 = User::factory()->create(['role' => 'employer']);
        Company::create(['user_id' => $employer2->id, 'name' => 'PT Lain', 'location' => 'Bandung']);
        $otherJob = $this->createJobPosting($employer2->company);

        $response = $this->actingAs($employer1)->get(route('job-postings.show', $otherJob));

        $response->assertStatus(403);
    }

    public function test_job_seeker_cannot_access_job_posting_routes(): void
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $employer = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($employer->company);

        $this->actingAs($seeker)->get(route('job-postings.index'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('job-postings.create'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('job-postings.show', $job))->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_job_posting_routes(): void
    {
        $response = $this->get(route('job-postings.index'));

        $response->assertRedirect('/login');
    }

    public function test_validation_works_on_create(): void
    {
        $user = $this->createEmployerWithCompany();

        $response = $this->actingAs($user)->post(route('job-postings.store'), []);

        $response->assertSessionHasErrors(['title', 'description', 'location', 'employment_type']);
    }

    public function test_validation_works_on_update(): void
    {
        $user = $this->createEmployerWithCompany();
        $job = $this->createJobPosting($user->company);

        $response = $this->actingAs($user)->put(route('job-postings.update', $job), [
            'title' => '',
            'description' => '',
            'location' => '',
            'employment_type' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'description', 'location', 'employment_type']);
    }

    public function test_index_only_shows_own_companies_jobs(): void
    {
        $employer1 = $this->createEmployerWithCompany();
        $employer2 = User::factory()->create(['role' => 'employer']);
        Company::create(['user_id' => $employer2->id, 'name' => 'PT Lain', 'location' => 'Bandung']);

        $myJob = $this->createJobPosting($employer1->company, ['title' => 'My Job']);
        $otherJob = $this->createJobPosting($employer2->company, ['title' => 'Other Job']);

        $response = $this->actingAs($employer1)->get(route('job-postings.index'));

        $response->assertSee('My Job');
        $response->assertDontSee('Other Job');
    }

    public function test_salary_min_must_be_less_than_salary_max(): void
    {
        $user = $this->createEmployerWithCompany();

        $response = $this->actingAs($user)->post(route('job-postings.store'), [
            'title' => 'Test Job',
            'description' => 'Test description.',
            'location' => 'Jakarta',
            'employment_type' => 'Full-time',
            'salary_min' => 10000000,
            'salary_max' => 5000000,
        ]);

        $response->assertSessionHasErrors('salary_max');
    }

    public function test_employer_without_company_gets_404(): void
    {
        $user = User::factory()->create(['role' => 'employer']);

        $response = $this->actingAs($user)->get(route('job-postings.index'));

        $response->assertStatus(404);
    }
}
