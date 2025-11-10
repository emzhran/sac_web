<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;   

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $allLapanganNames = Lapangan::select('nama')
            ->get()
            ->map(fn($lap) => explode(' ', trim($lap->nama))[0]) 
            ->unique()
            ->values();

        $lapanganFilterName = $request->query('lapangan');
        if (!$lapanganFilterName && $allLapanganNames->isNotEmpty()) {
            $lapanganFilterName = $allLapanganNames->first();
        }

        $lapangans = Lapangan::where('nama', 'LIKE', $lapanganFilterName . '%')
            ->orWhere('nama', $lapanganFilterName)
            ->get();

        $dates = $this->generateDateRange(7);
        $start = now()->toDateString();
        $end = now()->addDays(6)->toDateString();

        $currentLapangan = $lapangans->first();
        $allBookings = [];

        if ($currentLapangan) {
            $lapanganFilterName = $currentLapangan->nama;
            $allBookings[$lapanganFilterName] = $this->fetchBookings($currentLapangan, $start, $end);
        }

        $timeSlots = $this->generateTimeSlots(7, 22);

        return view('booking.index', compact('dates', 'timeSlots', 'allBookings', 'lapanganFilterName', 'lapangans'));
    }

    public function create(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
        ]);

        $tanggal = $request->query('tanggal');
        $jam_mulai = $request->query('jam_mulai');
        $jam_selesai = $request->query('jam_selesai');

        $isConflict = $this->checkConflict($lapangan->id, $tanggal, $jam_mulai, $jam_selesai);
        
        if ($isConflict) {
            return redirect()->route('booking.index', ['lapangan' => $lapangan->nama])->with('error', 'Slot jam yang Anda pilih sudah terisi. Silakan pilih slot lain.');
        }

        return view('booking.create', compact('lapangan', 'tanggal', 'jam_mulai', 'jam_selesai'));
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        try {
            $validated = $request->validate([
                'tanggal_booking' => 'required|date|after_or_equal:today', 
                'jam_mulai' => 'required|date_format:H:i|before:jam_selesai',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            ]);

            $menitMulai = (int)substr($validated['jam_mulai'], 3, 2);
            $menitSelesai = (int)substr($validated['jam_selesai'], 3, 2);
            if ($menitMulai !== 0 || $menitSelesai !== 0) {
                throw ValidationException::withMessages([
                    'jam_mulai' => ['Jam harus diakhiri dengan menit 00 (contoh: 10:00).'],
                ]);
            }

            $tanggal = $validated['tanggal_booking']; 
            $lapanganId = $lapangan->id;
            
            $isConflict = $this->checkConflict($lapanganId, $tanggal, $validated['jam_mulai'], $validated['jam_selesai']);

            if ($isConflict) {
                return response()->json([
                    'message' => 'Tidak dapat membooking lapangan ' . $lapangan->nama . ' karena sudah dibooking pada jam tersebut.',
                ], 409);
            }

            $booking = Booking::create([
                'lapangan_id' => $lapanganId,
                'user_id' => Auth::id(),
                'nama_pemesan' => Auth::user()->name,
                'status' => 'pending',
            ]);

            $booking->jadwals()->create([
                'tanggal' => $tanggal,
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
            ]);

            return response()->json([
                'message' => 'Pemesanan lapangan ' . $lapangan->nama . ' berhasil diajukan!'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan sistem saat memproses pemesanan.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    protected function checkConflict($lapanganId, $tanggal, $jamMulai, $jamSelesai)
    {
        return Jadwal::where('tanggal', $tanggal)
            ->whereHas('booking', function (Builder $query) use ($lapanganId) {
                $query->where('lapangan_id', $lapanganId);
            })
            ->where(function (Builder $query) use ($jamMulai, $jamSelesai) {
                $query->where(function (Builder $q) use ($jamMulai, $jamSelesai) {
                    $q->where('jam_mulai', '<', $jamSelesai)
                      ->where('jam_selesai', '>', $jamMulai);
                });
            })
            ->exists();
    }
}
