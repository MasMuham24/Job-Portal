<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun');
    }

    public function test_user_can_register_with_default_role_job_seeker(): void
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

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk Akun');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'siti@example.com',
            'password' => 'password123',
            'role' => 'employer',
        ]);

        $response = $this->post('/login', [
            'email' => 'siti@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('employer.dashboard'));
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'siti@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'siti@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_dashboard_displays_user_name_and_role(): void
    {
        $user = User::factory()->create([
            'name' => 'Ahmad Dani',
            'role' => 'job_seeker',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ahmad Dani');
        $response->assertSee('job_seeker');
        $response->assertSee('Logout');
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_cannot_access_login_or_register(): void
    {
        $user = User::factory()->create();

        $responseLogin = $this->actingAs($user)->get('/login');
        $responseLogin->assertRedirect('/dashboard');

        $responseRegister = $this->actingAs($user)->get('/register');
        $responseRegister->assertRedirect('/dashboard');
    }
}
