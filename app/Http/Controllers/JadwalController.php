<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lapangan; 
use App\Models\Jadwal;   
use App\Models\Booking; 
use Illuminate\Database\Eloquent\Builder; 

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $lapangans = Lapangan::all();
        $lapanganFilterName = $request->query('lapangan');
        if (!$lapanganFilterName && $lapangans->isNotEmpty()) {
            $lapanganFilterName = $lapangans->first()->nama;
        }

        $dates = $this->generateDateRange(7);
        $start = Carbon::today()->toDateString();
        $end = Carbon::today()->addDays(6)->toDateString();

        $lapangan = Lapangan::where('nama', 'LIKE', $lapanganFilterName . '%')->first();

        $allBookings = [];

        if ($lapangan) {
            $lapanganFilterName = $lapangan->nama;
            $allBookings[$lapanganFilterName] = $this->fetchBookings($lapangan, $start, $end);
        }

        $timeSlots = $this->generateTimeSlots(7, 22);

        return view('booking.index', compact('dates', 'timeSlots', 'allBookings', 'lapanganFilterName', 'lapangans'));
    }

    public function adminIndex(Request $request)
    {
        $allLapangans = \App\Models\Lapangan::orderBy('nama', 'asc')->get();
        $defaultLap = $allLapangans->first(); 
        $defaultLapName = $defaultLap ? $defaultLap->nama : 'Lapangan Default (Tidak Ada)';
        $lapanganFilterName = $request->query('lapangan', $defaultLapName);
        $lapangan = $allLapangans->firstWhere('nama', $lapanganFilterName);
        if (!$lapangan) {
            $lapangan = $allLapangans->filter(fn($lap) => str_starts_with($lap->nama, $lapanganFilterName))->first();
        }
        if (!$lapangan) {
            $lapangan = $defaultLap;
        }
        if ($lapangan) {
            $lapanganFilterName = $lapangan->nama;
        }
        
        $dates = $this->generateDateRange(7);
        $start = Carbon::today()->toDateString();
        $end = Carbon::today()->addDays(6)->toDateString();
        $allBookings = [];

        if ($lapangan) {
            $allBookings[$lapanganFilterName] = $this->fetchBookings($lapangan, $start, $end);
        } else {
            $lapanganFilterName = 'Tidak Ada Lapangan Ditemukan';
        }
        
        $timeSlots = $this->generateTimeSlots(7, 22);

        // Kirim $allLapangans agar navigasi Blade berfungsi
        return view('admin.jadwal.index', compact('dates', 'timeSlots', 'allBookings', 'lapanganFilterName', 'allLapangans'));
    }


    protected function fetchBookings(Lapangan $lapangan, $start, $end)
    {
        $jadwals = Jadwal::whereBetween('tanggal', [$start, $end])
            ->whereHas('booking', function (Builder $query) use ($lapangan) {
                $query->where('lapangan_id', $lapangan->id);
            })
            ->with('booking') 
            ->get();
        
        $formattedBookings = [];

        foreach ($jadwals as $jadwal) {
            $booking = $jadwal->booking;

            if ($booking) {
                $tanggal = $jadwal->tanggal;

                $formattedBookings[$tanggal][] = [
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'nama' => $booking->nama_pemesan,
                    'status' => $booking->status,
                    'booking_id' => $booking->id,
                ];
            }
        }
        
        return $formattedBookings;
    }

    protected function generateDateRange($days)
    {
        $dates = [];
        $start = Carbon::today();
        for ($i = 0; $i < $days; $i++) {
            $dates[] = $start->copy()->addDays($i);
        }
        return $dates;
    }

    protected function generateTimeSlots($startHour, $endHour)
    {
        $timeSlots = [];
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }
        return $timeSlots;
    }
}