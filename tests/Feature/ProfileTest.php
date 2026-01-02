<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_user_role_cannot_update_profile_information(): void
    {
        // Setup: User dengan data awal
        $user = User::factory()->create([
            'role' => 'user',
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);

        // Action: Coba update ke /profile
        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        // Assert: Harus Forbidden (403) sesuai logika controller
        $response->assertStatus(403); 

        // Assert: Data di database TIDAK berubah
        $user->refresh();
        $this->assertSame('Original Name', $user->name);
        $this->assertSame('original@example.com', $user->email);
    }

    public function test_admin_can_update_user_profile_information(): void
    {
        // Setup: Admin dan User target
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'role' => 'user',
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        // Action: Admin melakukan update ke route admin (bukan /profile)
        // Pastikan route '/admin/users/{id}' sudah didefinisikan di routes/web.php
        $response = $this
            ->actingAs($admin)
            ->patch("/admin/users/{$user->id}", [ 
                'name' => 'Updated by Admin',
                'email' => 'updated@example.com',
            ]);

        // Assert: Berhasil dan Redirect
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(); 

        // Assert: Data user berubah
        $user->refresh();
        $this->assertSame('Updated by Admin', $user->name);
        $this->assertSame('updated@example.com', $user->email);
    }
}