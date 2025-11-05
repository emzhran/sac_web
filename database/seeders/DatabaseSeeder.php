<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Kelompok 3',
            'email' => 'kelompok3@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'sac@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);
    }
}
