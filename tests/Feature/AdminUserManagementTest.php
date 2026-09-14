<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_access_user_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Pengguna');
    }

    public function test_admin_can_seek_users_by_name(): void
    {
        $admin = $this->createAdmin();
        User::factory()->create(['name' => 'Budi Santoso', 'role' => 'job_seeker']);
        User::factory()->create(['name' => 'Siti Rahayu', 'role' => 'employer']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Budi']));
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Rahayu');
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $admin = $this->createAdmin();
        User::factory()->create(['name' => 'Seeker User', 'role' => 'job_seeker']);
        User::factory()->create(['name' => 'Employer User', 'role' => 'employer']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'employer']));
        $response->assertSee('Employer User');
        $response->assertDontSee('Seeker User');
    }

    public function test_admin_can_open_create_user_form(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Pengguna Baru');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Ahmad Dani',
            'email' => 'ahmad@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $user = User::where('email', 'ahmad@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('job_seeker', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_admin_can_create_employer_user(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Siti Employer',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'employer',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'siti@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('employer', $user->role);
    }

    public function test_admin_create_user_validation_errors(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'invalid_role',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_create_user_unique_email(): void
    {
        $admin = $this->createAdmin();
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Test',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'job_seeker',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_open_edit_user_form(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['name' => 'Original Name']);

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertSee('Edit Pengguna');
        $response->assertSee('Original Name');
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'job_seeker',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'employer',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('employer', $user->role);
    }

    public function test_admin_can_change_user_role(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['role' => 'job_seeker']);

        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'employer',
        ]);

        $user->refresh();
        $this->assertEquals('employer', $user->role);
    }

    public function test_admin_can_update_password_when_provided(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['password' => 'oldpassword', 'role' => 'job_seeker']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'job_seeker',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        $this->assertFalse(Hash::check('oldpassword', $user->password));
    }

    public function test_admin_password_unchanged_when_empty(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['password' => 'keepthispassword', 'role' => 'job_seeker']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'job_seeker',
            'password' => '',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertTrue(Hash::check('keepthispassword', $user->password));
    }

    public function test_admin_update_unique_email_except_self(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['email' => 'user@example.com', 'role' => 'job_seeker']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => 'user@example.com',
            'role' => 'job_seeker',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $user->refresh();
        $this->assertEquals('user@example.com', $user->email);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['role' => 'job_seeker']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_employer_cannot_access_admin_user_management(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $user = User::factory()->create();

        $this->actingAs($employer)->get(route('admin.users.index'))->assertStatus(403);
        $this->actingAs($employer)->get(route('admin.users.create'))->assertStatus(403);
        $this->actingAs($employer)->get(route('admin.users.edit', $user))->assertStatus(403);
        $this->actingAs($employer)->post(route('admin.users.store'), [
            'name' => 'Test', 'email' => 'test@test.com', 'password' => 'password123',
            'password_confirmation' => 'password123', 'role' => 'job_seeker',
        ])->assertStatus(403);
        $this->actingAs($employer)->put(route('admin.users.update', $user), [
            'name' => 'Test', 'email' => 'test@test.com', 'role' => 'job_seeker',
        ])->assertStatus(403);
        $this->actingAs($employer)->delete(route('admin.users.destroy', $user))->assertStatus(403);
    }

    public function test_job_seeker_cannot_access_admin_user_management(): void
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $user = User::factory()->create();

        $this->actingAs($seeker)->get(route('admin.users.index'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('admin.users.create'))->assertStatus(403);
        $this->actingAs($seeker)->get(route('admin.users.edit', $user))->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_user_management(): void
    {
        $user = User::factory()->create();

        $this->get(route('admin.users.index'))->assertRedirect('/login');
        $this->get(route('admin.users.create'))->assertRedirect('/login');
        $this->get(route('admin.users.edit', $user))->assertRedirect('/login');
    }

    public function test_admin_delete_cascades_company(): void
    {
        $admin = $this->createAdmin();
        $employer = User::factory()->create(['role' => 'employer']);
        Company::create([
            'user_id' => $employer->id,
            'name' => 'PT Test',
            'location' => 'Jakarta',
        ]);

        $this->assertDatabaseHas('companies', ['user_id' => $employer->id]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $employer));

        $this->assertDatabaseMissing('users', ['id' => $employer->id]);
        $this->assertDatabaseMissing('companies', ['user_id' => $employer->id]);
    }
}
