<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lapangan;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lapangans = [
            ['nama' => 'Futsal'],
            ['nama' => 'Badminton'],
            ['nama' => 'Voli'],
            ['nama' => 'Basket'],
        ];

        foreach ($lapangans as $lapangan) {
            Lapangan::create($lapangan);
        }
    }
}