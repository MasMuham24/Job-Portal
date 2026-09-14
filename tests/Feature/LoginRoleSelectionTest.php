<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginRoleSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_shows_role_selection(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Pilih Tipe Akun');
        $response->assertSee('Pencari Kerja');
        $response->assertSee('Rekruter');
        $response->assertSee('job_seeker');
        $response->assertSee('employer');
    }

    public function test_register_defaults_to_job_seeker(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('job_seeker');
    }

    public function test_user_can_register_as_job_seeker(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'job_seeker',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $user = User::where('email', 'budi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('job_seeker', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_can_register_as_employer_with_company(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'employer',
            'company_name' => 'PT Digital Nusantara',
            'company_location' => 'Jakarta Selatan',
            'company_website' => 'https://www.digitalnusantara.id',
            'company_phone' => '021-5551234',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $user = User::where('email', 'siti@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('employer', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));

        $company = Company::where('user_id', $user->id)->first();
        $this->assertNotNull($company);
        $this->assertEquals('PT Digital Nusantara', $company->name);
        $this->assertEquals('Jakarta Selatan', $company->location);
        $this->assertEquals('https://www.digitalnusantara.id', $company->website);
        $this->assertEquals('021-5551234', $company->phone);
    }

    public function test_employer_registration_requires_company_name(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'employer',
            'company_name' => '',
        ]);

        $response->assertSessionHasErrors('company_name');
    }

    public function test_employer_registration_without_company_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'employer',
            'company_name' => 'PT Test',
        ]);

        $user = User::where('email', 'siti@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('employer', $user->role);

        $company = Company::where('user_id', $user->id)->first();
        $this->assertNotNull($company);
        $this->assertEquals('PT Test', $company->name);
        $this->assertNull($company->location);
        $this->assertNull($company->website);
        $this->assertNull($company->phone);
    }

    public function test_job_seeker_registration_does_not_create_company(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'job_seeker',
        ]);

        $user = User::where('email', 'budi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('job_seeker', $user->role);

        $this->assertDatabaseMissing('companies', ['user_id' => $user->id]);
    }

    public function test_registration_requires_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_registration_rejects_invalid_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_rejects_job_seeker_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_login_page_displays_pencari_kerja_option(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Pencari Kerja');
        $response->assertSee('job_seeker');
    }

    public function test_login_page_displays_rekruter_option(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Rekruter');
        $response->assertSee('employer');
    }

    public function test_job_seeker_can_login_with_job_seeker_selection(): void
    {
        $user = User::factory()->create([
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response = $this->post('/login', [
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_employer_can_login_with_employer_selection(): void
    {
        $user = User::factory()->create([
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $response = $this->post('/login', [
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('employer.dashboard'));
    }

    public function test_job_seeker_cannot_login_using_employer_selection(): void
    {
        User::factory()->create([
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response = $this->post('/login', [
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['role' => 'Role login tidak sesuai dengan akun Anda.']);
    }

    public function test_employer_cannot_login_using_job_seeker_selection(): void
    {
        User::factory()->create([
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $response = $this->post('/login', [
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['role' => 'Role login tidak sesuai dengan akun Anda.']);
    }

    public function test_wrong_role_does_not_leave_the_user_authenticated(): void
    {
        User::factory()->create([
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response = $this->post('/login', [
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $this->assertGuest();
        $this->assertNull(auth()->user());
        $response->assertSessionHasErrors('role');
    }

    public function test_admin_login_continues_to_work(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_authenticate_using_employer_selection(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['role' => 'Role login tidak sesuai dengan akun Anda.']);
    }

    public function test_invalid_credentials_remain_rejected(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
            'role' => 'job_seeker',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_successful_login_redirects_correctly(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        $responseAdmin = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $responseAdmin->assertRedirect(route('admin.dashboard'));

        $this->post('/logout');

        $employer = User::factory()->create([
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);
        $responseEmployer = $this->post('/login', [
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);
        $responseEmployer->assertRedirect(route('employer.dashboard'));

        $this->post('/logout');

        $seeker = User::factory()->create([
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);
        $responseSeeker = $this->post('/login', [
            'email' => 'seeker@example.com',
            'password' => 'password123',
            'role' => 'job_seeker',
        ]);
        $responseSeeker->assertRedirect('/dashboard');
    }

    public function test_registration_form_shows_company_fields_for_employer(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('company_name');
        $response->assertSee('company_location');
        $response->assertSee('company_website');
        $response->assertSee('company_phone');
    }

    public function test_register_validation_errors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }
}
