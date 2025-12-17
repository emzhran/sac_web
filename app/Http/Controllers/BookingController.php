<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
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
    $user = Auth::user();
    
    // Gunakan Waktu WIB
    $now = Carbon::now('Asia/Jakarta');

    // ============================================================
    // A. AUTO RESET (PEMUTIHAN - JIKA MASA HUKUMAN SUDAH LEWAT)
    // ============================================================
    if ($user->suspended_until && $now->greaterThanOrEqualTo($user->suspended_until)) {
        // 1. Reset Status User
        $user->update([
            'missed_attendance_count' => 0, 
            'suspended_until' => null       
        ]);

        // 2. Putihkan / Cancel Booking Lama yang Mangkir
        $oldBookings = Booking::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereNull('confirmed_at')
            ->with('jadwals')
            ->get();

        foreach($oldBookings as $old) {
            $jadwal = $old->jadwals->first();
            if($jadwal) {
                // Parse tanggal jadwal
                $waktuSelesai = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_selesai, 'Asia/Jakarta');
                // Hanya cancel jika jadwalnya MEMANG SUDAH LEWAT
                if($waktuSelesai->lessThan($now)) {
                    $old->update(['status' => 'cancelled']);
                }
            }
        }
    }

    // ============================================================
    // B. CEK STATUS BANNED (BLOCKING)
    // ============================================================
    if ($user->suspended_until && $now->lessThan($user->suspended_until)) {
        $tanggalBebas = Carbon::parse($user->suspended_until)->translatedFormat('d F Y H:i');
        return redirect()->route('booking.index')
            ->with('show_suspend_modal', $tanggalBebas);
    }

    // ============================================================
    // C. HITUNG MANGKIR & TENTUKAN TANGGAL HUKUMAN
    // ============================================================
    
    // 1. Ambil semua booking yang berpotensi mangkir
    $potensiMangkir = Booking::where('user_id', $user->id)
        ->where('status', 'approved')
        ->whereNull('confirmed_at')
        ->with('jadwals') 
        ->get();

    $listTanggalMangkir = [];

    // 2. Loop untuk memvalidasi tanggal (PHP Logic)
    foreach ($potensiMangkir as $b) {
        $jadwal = $b->jadwals->first();
        if ($jadwal) {
            try {
                $waktuSelesai = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_selesai, 'Asia/Jakarta');
                
                // Jika waktu selesai sudah lewat dari sekarang, catat sebagai pelanggaran
                if ($waktuSelesai->lessThan($now)) {
                    // Masukkan ke array untuk dicari tanggal terbarunya nanti
                    $listTanggalMangkir[] = $waktuSelesai;
                }
            } catch (\Exception $e) { continue; }
        }
    }

    $jumlahMangkir = count($listTanggalMangkir);

    // 3. EKSEKUSI BAN (JIKA SUDAH 3x)
    if ($jumlahMangkir >= 3) {
        
        // --- LOGIKA BARU: Cari Tanggal Mangkir Paling Baru (Terakhir) ---
        
        // Urutkan array tanggal dari yang paling baru (Descending)
        usort($listTanggalMangkir, function($a, $b) {
            return $b->timestamp - $a->timestamp; 
        });

        // Ambil tanggal paling baru (index 0 setelah diurutkan)
        $tanggalTerakhirMangkir = $listTanggalMangkir[0];

        // Hitung hukuman: 1 Bulan DARI TANGGAL MANGKIR TERAKHIR (Bukan dari sekarang)
        $suspendedUntil = $tanggalTerakhirMangkir->copy()->addMonth();
        
        // Update User
        $user->update([
            'missed_attendance_count' => $jumlahMangkir,
            'suspended_until' => $suspendedUntil
        ]);

        // Cek: Apakah hukuman itu masih berlaku SAAAT INI?
        // (Bisa jadi dia mangkir 3 bulan lalu, jadi hukumannya sebenernya udah lewat)
        if ($now->lessThan($suspendedUntil)) {
            // Jika masih dalam masa hukuman, tampilkan modal
            $tanggalBebas = $suspendedUntil->translatedFormat('d F Y');
            return redirect()->route('booking.index')
                ->with('show_suspend_modal', $tanggalBebas);
        } else {
            // Jika ternyata hukumannya sudah expired (misal dia login telat banget),
            // Kita langsung jalankan Auto Reset (Recursive call) atau biarkan dia lanjut booking
            // Untuk aman, kita biarkan dia lanjut booking, nanti saat submit (store) atau refresh akan ter-reset otomatis di blok A.
        }
    }

    // ============================================================
    // D. VALIDASI LAINNYA
    // ============================================================
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('booking.index', ['lapangan' => $lapangan->nama])
            ->with('email_unverified', true);
    }

    $request->validate([
        'tanggal' => 'required|date|after_or_equal:today',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    ]);

    // ... (Sisa kode Anda sama persis ke bawah) ...
    $tanggal = $request->query('tanggal');
    $jam_mulai = $request->query('jam_mulai');
    $jam_selesai = $request->query('jam_selesai');

    $start = Carbon::createFromFormat('H:i', $jam_mulai);
    $end = Carbon::createFromFormat('H:i', $jam_selesai);

    if ($start->diffInMinutes($end) > 180) {
        return redirect()->route('booking.index', ['lapangan' => $lapangan->nama])
            ->with('error', 'Maksimal durasi booking hanya boleh 3 jam.');
    }

    $isConflict = $this->checkConflict($lapangan->id, $tanggal, $jam_mulai, $jam_selesai);
    
    if ($isConflict) {
        return redirect()->route('booking.index', ['lapangan' => $lapangan->nama])
            ->with('error', 'Jam yang Anda pilih sudah terisi. Silakan pilih jam lain.');
    }

    return view('booking.create', compact('lapangan', 'tanggal', 'jam_mulai', 'jam_selesai'));
}

    public function store(Request $request, Lapangan $lapangan)
    {
        $user = Auth::user();

        if ($user->suspended_until && Carbon::now()->lessThan($user->suspended_until)) {
            return redirect()->route('booking.index')
                ->with('error', 'Akun Anda sedang ditangguhkan.');
        }

        if (!$user->hasVerifiedEmail()) {
            return back()->with('error', 'Harap verifikasi email Anda terlebih dahulu.');
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $tanggal = $request->tanggal;
        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;
        $start = Carbon::createFromFormat('H:i', $jamMulai);
        $end = Carbon::createFromFormat('H:i', $jamSelesai);

        if ($start->diffInMinutes($end) > 180) {
            return back()->with('error', 'Maksimal durasi booking hanya boleh 3 jam.');
        }

        $isConflict = $this->checkConflict($lapangan->id, $tanggal, $jamMulai, $jamSelesai);

        if ($isConflict) {
            return back()->with('error', 'Jam yang Anda pilih sudah terisi.');
        }

        $booking = Booking::create([
            'lapangan_id' => $lapangan->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        $booking->jadwals()->create([
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', 'Pemesanan berhasil diajukan!');
    }



    protected function fetchBookings(Lapangan $lapangan, $start, $end)
    {
        $jadwals = Jadwal::whereBetween('tanggal', [$start, $end])
            ->whereHas('booking', function (Builder $query) use ($lapangan) {
                $query->where('lapangan_id', $lapangan->id);
            })
            ->with('booking.user')
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