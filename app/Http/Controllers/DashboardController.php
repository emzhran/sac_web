<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking; // Pastikan model Booking ada
use App\Models\Lapangan; // Pastikan model Lapangan ada
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil semua booking milik user ini (berdasarkan nama karena tidak ada user_id di schema)
        // Disarankan: Tambahkan user_id di tabel bookings kedepannya.
        $myBookings = Booking::with(['lapangan', 'jadwal'])
                        ->where('nama_pemesan', $user->name)
                        ->latest()
                        ->get();

        // Statistik
        $totalBookings = $myBookings->count();
        $pendingBookings = $myBookings->where('status', 'pending')->count();
        
        // Mencari jadwal bermain selanjutnya (yang status approved dan tanggal >= hari ini)
        $nextSchedule = null;
        $approvedBookings = $myBookings->where('status', 'approved');
        
        foreach($approvedBookings as $booking) {
            foreach($booking->jadwal as $jadwal) {
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

        // List semua lapangan untuk kartu booking
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
