<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'nim' => '20230140001',
            'fakultas' => 'Teknik',
            'prodi' => 'Teknologi Informasi',
            'email' => 'testuser@gmail.com',
            'password' => 'P4ssw0rd!',
            'password_confirmation' => 'P4ssw0rd!',
        ]);

        $this->assertGuest();

        $response->assertRedirect(route('login'));

        $response->assertSessionHas('status', 'Registrasi berhasil! Silakan cek email anda untuk verifikasi akun.');
        
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@gmail.com',
            'nim' => '20230140001',
        ]);
    }
}