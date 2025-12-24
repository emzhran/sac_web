<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationCheckTest extends TestCase
{
    use RefreshDatabase;
    const UMY_LAT = -7.811345;
    const UMY_LNG = 110.320745;

    public function test_user_inside_radius_can_confirm_presence()
    {
        $user = User::factory()->create();
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
            'confirmed_at' => null,
        ]);

        $simulatedLat = -7.811350; 
        $simulatedLng = 110.320750;

        $response = $this->actingAs($user)
            ->post(route('booking.confirm', $booking->id), [
                'latitude' => $simulatedLat,
                'longitude' => $simulatedLng,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
        $this->assertNotNull($booking->fresh()->confirmed_at);
    }

    public function test_user_outside_radius_cannot_confirm_presence()
    {
        $user = User::factory()->create();
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
            'confirmed_at' => null,
        ]);

        $tuguLat = -7.7829;
        $tuguLng = 110.3670;

        $response = $this->actingAs($user)
            ->post(route('booking.confirm', $booking->id), [
                'latitude' => $tuguLat,
                'longitude' => $tuguLng,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertNull($booking->fresh()->confirmed_at);
    }

    public function test_request_without_coordinates_fails_validation()
    {
        $user = User::factory()->create();
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)
            ->post(route('booking.confirm', $booking->id), []);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
        
        $this->assertNull($booking->fresh()->confirmed_at);
    }
    
    public function test_cannot_confirm_pending_booking()
    {
        $user = User::factory()->create();
        $lapangan = Lapangan::factory()->create();
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post(route('booking.confirm', $booking->id), [
                'latitude' => -7.811345,
                'longitude' => 110.320745,
            ]);

        $response->assertSessionHas('error');
        $this->assertNull($booking->fresh()->confirmed_at);
    }
}