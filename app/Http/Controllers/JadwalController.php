<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Futsal;
use App\Models\Badminton;
use App\Models\Voli;


class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $lapanganFilter = $request->query('lapangan', 'Futsal');

        $dates = $this->generateDateRange(7);
        $start = Carbon::today();
        $end = Carbon::today()->addDays(6)->endOfDay();

        $allBookings = [];

        if ($lapanganFilter === 'Futsal') {
            $allBookings['Futsal'] = $this->fetchBookings(Futsal::class, $start, $end);
        } elseif ($lapanganFilter === 'Badminton') {
            $allBookings['Badminton'] = $this->fetchBookings(Badminton::class, $start, $end);
        } elseif ($lapanganFilter === 'Voli') {
            $allBookings['Voli'] = $this->fetchBookings(Voli::class, $start, $end);
        } elseif ($lapanganFilter === 'Basket') {
            $allBookings['Basket'] = $this->fetchBookings(Basket::class, $start, $end);
        }

        $timeSlots = $this->generateTimeSlots(7, 22);

        return view('lapangan.jadwal-lapangan', compact('dates', 'timeSlots', 'allBookings', 'lapanganFilter'));
    }

    protected function fetchBookings($modelClass, $start, $end)
    {
        return $modelClass::whereBetween('jadwal', [$start, $end])
            ->get(['nama', 'jadwal', 'jam_mulai', 'jam_selesai', 'status'])
            ->map(function ($item) {
                $tanggal = Carbon::parse($item->jadwal)->toDateString();

                return [
                    'tanggal' => $tanggal,
                    'jam_mulai' => Carbon::parse($item->jam_mulai)->format('H:i'),
                    'jam_selesai' => Carbon::parse($item->jam_selesai)->format('H:i'),
                    'nama' => $item->nama,
                    'status' => $item->status,
                ];
            })
            ->groupBy('tanggal')
            ->toArray();
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
