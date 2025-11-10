<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('users')->truncate(); 
        
        DB::table('jadwals')->truncate(); 
        DB::table('bookings')->truncate(); 
        DB::table('lapangans')->truncate(); 

        $this->call([
            LapanganSeeder::class,
        ]);
        
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
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}