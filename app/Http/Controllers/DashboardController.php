<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Lapangan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myBookings = Booking::with(['lapangan', 'jadwals'])
                        ->where('user_id', $user->id)
                        ->latest()
                        ->get();

        $totalBookings = $myBookings->count();
        $pendingBookings = $myBookings->where('status', 'pending')->count();
        
        $nextSchedule = null;
        $approvedBookings = $myBookings->where('status', 'approved');
        
        foreach($approvedBookings as $booking) {
            foreach($booking->jadwals as $jadwal) {
                $jadwalDate = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_mulai);
                
                if($jadwalDate->isFuture()) {
                    if(!$nextSchedule || $jadwalDate->lessThan($nextSchedule['datetime'])) {
                        $nextSchedule = [
                            'field' => $booking->lapangan->nama,
                            'date' => $jadwalDate->format('d M Y'),
                            'time' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai,
                            'datetime' => $jadwalDate
                        ];
                    }
                }
            }
        }

        $fields = Lapangan::all();

        return view('dashboard', compact(
            'user', 
            'totalBookings', 
            'pendingBookings', 
            'nextSchedule', 
            'myBookings',
            'fields'
        ));
    }
}