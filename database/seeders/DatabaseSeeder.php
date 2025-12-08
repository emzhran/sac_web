<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); 
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('users')->truncate();
        DB::table('jadwals')->truncate();
        DB::table('bookings')->truncate();
        DB::table('lapangans')->truncate();

        $this->call([
            LapanganSeeder::class,
        ]);

        $dataPendidikan = [
            "Teknik" => ["Teknik Sipil", "Teknik Mesin", "Teknik Elektro", "Teknologi Informasi", "Teknologi Elektro-Medis", "Teknologi Rekayasa Otomotif"],
            "Agama Islam" => ["Komunikasi dan Penyiaran Islam", "Pendidikan Agama Islam", "Ekonomi Syariah"],
            "Kedokteran & Ilmu Kesehatan" => ["Kedokteran", "Pendidikan Profesi Dokter", "Pendidikan Profesi Ners", "Farmasi", "Apoteker", "Keperawatan"],
            "Kedokteran Gigi" => ["Kedokteran Gigi", "Profesi Dokter Gigi"],
            "Pertanian" => ["Agroteknologi", "Agribisnis"],
            "Ilmu Sosial Politik" => ["Hubungan Internasional", "Ilmu Komunikasi", "Ilmu Pemerintahan", "IPIREL", "IGOV", "IP-COS"],
            "Ekonomi & Bisnis" => ["Manajemen", "Akuntansi", "Ekonomi", "IMaBs", "IPAcc", "IPIEF", "Magister Manajemen"],
            "Pendidikan Bahasa" => ["Pendidikan Bahasa Inggris", "Pendidikan Bahasa Arab", "Pendidikan Bahasa Jepang"],
            "Hukum" => ["Hukum", "IPOLS"],
            "Psikologi" => ["Psikologi"]
        ];

        User::create([
            'name' => 'Admin User',
            'email' => 'sac@example.com',
            'nim' => '00000000',
            'fakultas' => 'Teknik', 
            'prodi' => 'Teknologi Informasi',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kelompok 3',
            'email' => 'kelompok3@example.com',
            'nim' => '20210140001',
            'fakultas' => 'Teknik',
            'prodi' => 'Teknologi Informasi',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
        
        for ($i = 1; $i <= 10; $i++) {
            $fakultasKey = array_rand($dataPendidikan);
            $prodiArray = $dataPendidikan[$fakultasKey];
            $prodiValue = $prodiArray[array_rand($prodiArray)];

            User::create([
                'name' => "Mahasiswa User $i", 
                'email' => "user{$i}@student.umy.ac.id",
                'nim' => '2024' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'fakultas' => $fakultasKey,
                'prodi' => $prodiValue,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
