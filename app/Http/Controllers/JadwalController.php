<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lapangan;
use App\Models\Jadwal;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

        return view('admin.jadwal.index', compact('dates', 'timeSlots', 'allBookings', 'lapanganFilterName', 'allLapangans'));
    }
    public function create(Request $request)
    {
        $lapangans = Lapangan::all();
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        
        $selectedLapanganId = $request->query('lapangan_id');
        if ($selectedLapanganId) {
            $lapangan = Lapangan::find($selectedLapanganId);
        } else {
            $lapangan = $lapangans->first();
        }

        $tanggal = $request->query('tanggal', date('Y-m-d'));
        
        session()->flashInput(['jam_mulai' => $request->query('jam_mulai')]); 

        return view('admin.jadwal.create', compact(
            'lapangans',
            'users',
            'lapangan',
            'tanggal' 
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan_manual' => 'required|string|max:255',
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $isConflict = Jadwal::where('tanggal', $request->tanggal)
            ->whereHas('booking', function ($q) use ($request) {
                $q->where('lapangan_id', $request->lapangan_id)
                    ->where('status', '!=', 'rejected');
            })
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($isConflict) {
            return back()->with('error', 'Jadwal bentrok dengan booking yang sudah ada!')->withInput();
        }

        DB::transaction(function () use ($request) {

            $user = User::firstOrCreate(
                ['name' => $request->nama_pemesan_manual],
                [
                    'email' => Str::slug($request->nama_pemesan_manual) . '_' . uniqid() . '@guest.com', 
                    'password' => Hash::make('password'),
                    'role' => 'guest'
                ]
            );

            $booking = Booking::create([
                'user_id' => $user->id,
                'lapangan_id' => $request->lapangan_id,
                'status' => 'approved',
            ]);

            $booking->jadwals()->create([
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal manual berhasil ditambahkan.');
    }

    protected function fetchBookings(Lapangan $lapangan, $start, $end)
    {
        $jadwals = Jadwal::whereBetween('tanggal', [$start, $end])
            ->whereHas('booking', function (Builder $query) use ($lapangan) {
                $query->where('lapangan_id', $lapangan->id);
            })
            ->with(['booking.user'])
            ->get();

        $formattedBookings = [];

        foreach ($jadwals as $jadwal) {
            $booking = $jadwal->booking;

            if ($booking) {
                $tanggal = $jadwal->tanggal;
                $userName = $booking->user ? $booking->user->name : 'User Tidak Dikenal';

                $formattedBookings[$tanggal][] = [
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'nama' => $userName,
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