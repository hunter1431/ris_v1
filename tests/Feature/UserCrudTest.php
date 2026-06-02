<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        Role::create(['name' => 'admin']);
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_user_list_returns_users_and_roles(): void
    {
        $admin = $this->createAdminUser();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure(['users', 'roles']);
    }

    public function test_user_can_be_created(): void
    {
        $admin = $this->createAdminUser();
        Sanctum::actingAs($admin);

        Role::create(['name' => 'Employee/Requester']);

        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'new.user@example.com',
            'password' => 'password123',
            'role' => 'Employee/Requester',
        ]);

        $response->assertOk()
            ->assertJsonPath('email', 'new.user@example.com')
            ->assertJsonPath('roles.0.name', 'Employee/Requester');

        $this->assertDatabaseHas('users', ['email' => 'new.user@example.com']);
    }
}
