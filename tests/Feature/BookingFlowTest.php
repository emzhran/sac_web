<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_student_can_create_booking()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email_verified_at' => now(), 
        ]);

        $lapangan = Lapangan::factory()->create();
        $tanggal = Carbon::tomorrow()->toDateString();
        $jamMulai = '10:00';
        $jamSelesai = '12:00';

        $response = $this->actingAs($user)
            ->post(route('booking.store', $lapangan->id), [
                'tanggal' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
            ]);

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('jadwals', [
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);
    }

    public function test_unverified_student_cannot_create_booking()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        $lapangan = Lapangan::factory()->create();
        $response = $this->actingAs($user)
            ->post(route('booking.store', $lapangan->id), [
                'tanggal' => Carbon::tomorrow()->toDateString(),
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
            ]);
        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('jadwals', 0);
    }

    public function test_admin_can_approve_booking_status()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'pending',
        ]);

        Jadwal::create([
            'booking_id' => $booking->id,
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '14:00',
            'jam_selesai' => '15:00',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin', 
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.booking.update_status', [
                'booking' => $booking->id,
                'status' => 'approved'
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_reject_booking_status()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'pending',
        ]);

        Jadwal::create([
            'booking_id' => $booking->id,
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin', 
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.booking.update_status', [
                'booking' => $booking->id,
                'status' => 'rejected'
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'rejected',
        ]);
    }

    public function test_admin_can_create_manual_booking_immediately_approved()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $lapangan = Lapangan::factory()->create();

        $namaKegiatan = 'Turnamen Futsal UKM';
        $tanggal = Carbon::tomorrow()->toDateString();
        $jamMulai = '08:00';
        $jamSelesai = '12:00';
        $response = $this->actingAs($admin)
            ->post(route('admin.jadwal.store'), [
                'nama_pemesan_manual' => $namaKegiatan,
                'lapangan_id' => $lapangan->id,
                'tanggal' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
            ]);

        $response->assertRedirect(route('admin.jadwal.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'name' => $namaKegiatan,
            'role' => 'guest',
        ]);

        $newUser = User::where('name', $namaKegiatan)->first();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $newUser->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('jadwals', [
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);
    }
}