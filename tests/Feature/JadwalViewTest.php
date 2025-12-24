<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_schedule_page()
    {
        $user = User::factory()->create([
            'role' => 'user', 
            'email_verified_at' => now()
        ]);

        $lapangan = Lapangan::factory()->create(['nama' => 'Badminton']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
        ]);

        Jadwal::create([
            'booking_id' => $booking->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
        ]);

        $response = $this->actingAs($user)->get(route('booking.index'));

        $response->assertStatus(200);
        $response->assertViewIs('booking.index');
        $response->assertSee('Badminton'); 
        $response->assertViewHas('allBookings');
    }

    public function test_admin_can_view_schedule_page()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now()
        ]);
        $lapangan = Lapangan::factory()->create(['nama' => 'Futsal']);

        $user = User::factory()->create();
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
        ]);

        Jadwal::create([
            'booking_id' => $booking->id,
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '14:00',
            'jam_selesai' => '16:00',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.jadwal.index');
        $response->assertSee('Futsal');
        $response->assertViewHas('allLapangans');
    }
    
    public function test_schedule_can_be_filtered_by_field_name()
    {
        $user = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);
        $lapangan1 = Lapangan::factory()->create(['nama' => 'Basket']);
        $lapangan2 = Lapangan::factory()->create(['nama' => 'Voli']);
        $response = $this->actingAs($user)->get(route('booking.index', ['lapangan' => 'Voli']));

        $response->assertStatus(200);
        $response->assertViewHas('lapanganFilterName', 'Voli');
        $response->assertSee('Voli');
    }
}