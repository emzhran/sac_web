<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lapangan; 
use App\Models\Booking; 
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Setup Data Filter (Tahun, Bulan, Tanggal)
        $currentYear = now()->year;
        
        // Ambil input dari request atau gunakan default
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', now()->month);
        $selectedDay = $request->input('day'); // Bisa null (artinya semua tanggal di bulan tsb)

        // Generate list Tahun (5 tahun ke belakang sampai 1 tahun ke depan)
        $years = range($currentYear - 4, $currentYear + 1);
        
        // Generate list Bulan
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->translatedFormat('F');
        }

        // Generate list Tanggal (1-31)
        $days = range(1, 31);

        // 2. Buat Label Periode untuk Tampilan
        if ($selectedDay) {
            // Jika tanggal dipilih: "5 Januari 2024"
            $dateObj = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay);
            $periodLabel = $dateObj->translatedFormat('d F Y');
        } else {
            // Jika hanya bulan/tahun: "Januari 2024"
            $dateObj = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $periodLabel = $dateObj->translatedFormat('F Y');
        }

        // 3. Helper Query untuk Filter Waktu
        // Fungsi ini akan menerapkan filter 'where' ke query yang diberikan
        $applyDateFilter = function($query, $column = 'updated_at') use ($selectedYear, $selectedMonth, $selectedDay) {
            $query->whereYear($column, $selectedYear)
                  ->whereMonth($column, $selectedMonth);
            
            if ($selectedDay) {
                $query->whereDay($column, $selectedDay);
            }
            return $query;
        };

        // 4. Eksekusi Query Data Utama
        $successStatus = 'approved';
        $pendingStatus = 'pending';

        // Total Bookings (Approved)
        $bookingQuery = Booking::where('status', $successStatus);
        $totalBookings = $applyDateFilter($bookingQuery, 'updated_at')->count();

        // Pending Bookings
        $pendingQuery = Booking::where('status', $pendingStatus);
        $pendingBookings = $applyDateFilter($pendingQuery, 'updated_at')->count();

        // Usage Stats (Detail Lapangan)
        // Kita perlu query dasar dulu sebelum grouping
        $usageStatsQuery = Booking::select('lapangan_id', DB::raw('COUNT(*) as bookings_count'))
            ->where('status', $successStatus);
        
        $usageStats = $applyDateFilter($usageStatsQuery, 'updated_at')
            ->groupBy('lapangan_id')
            ->get();
        
        // 5. Olah Data Lapangan (Warna & Persentase)
        $lapangans = Lapangan::all();
        $fieldDetails = [];
        $maxBookings = 0;
        $mostUsedField = 'N/A';
        $colors = ['red', 'yellow', 'green', 'purple', 'indigo', 'pink'];
        $colorIndex = 0;

        $totalUsage = $usageStats->sum('bookings_count');
        if ($totalUsage == 0) $totalUsage = 1; 

        foreach ($lapangans as $lapangan) {
            $stat = $usageStats->where('lapangan_id', $lapangan->id)->first();
            $count = $stat ? $stat->bookings_count : 0;
            
            $displayName = strtok($lapangan->nama, ' '); // Ambil kata pertama

            $fieldDetails[] = [
                'name' => $displayName,
                'bookings' => $count,
                'percentage' => round(($count / $totalUsage) * 100, 1),
                'color' => $colors[$colorIndex % count($colors)],
            ];
            
            $colorIndex++;
            
            if ($count > $maxBookings) {
                $maxBookings = $count;
                $mostUsedField = $displayName;
            }
        }
        
        // 6. Data Penggunaan Fakultas
        // Note: Aslinya menggunakan created_at, kita pertahankan atau samakan ke updated_at sesuai kebutuhan.
        // Di sini saya gunakan created_at agar konsisten dengan logic asli Anda.
        $facultyQuery = Booking::select('users.fakultas', DB::raw('COUNT(bookings.id) as bookings'))
            ->join('users', 'bookings.nama_pemesan', '=', 'users.name') 
            ->where('bookings.status', $successStatus)
            ->whereNotNull('users.fakultas');

        // Terapkan filter tanggal (menggunakan created_at untuk booking ini)
        $applyDateFilter($facultyQuery, 'bookings.created_at');

        $facultyUsageRaw = $facultyQuery->groupBy('users.fakultas')->get();

        $allFaculties = [
            'Teknik', 'Agama Islam', 'Kedokteran & Ilmu Kesehatan', 'Kedokteran Gigi',
            'Pertanian', 'Ilmu Sosial Politik', 'Ekonomi & Bisnis', 'Pendidikan Bahasa',
            'Hukum', 'Psikologi',
        ];

        $facultyUsage = [];
        $rawCounts = $facultyUsageRaw->pluck('bookings', 'fakultas')->toArray();

        foreach ($allFaculties as $facultyName) {
            $facultyUsage[] = [
                'name' => $facultyName,
                'bookings' => $rawCounts[$facultyName] ?? 0,
            ];
        }

        // Sort dari yang terbanyak
        usort($facultyUsage, fn($a, $b) => $b['bookings'] <=> $a['bookings']);

        return view('dashboard-admin', compact(
            'totalBookings',
            'pendingBookings',
            'mostUsedField',
            'fieldDetails',
            'facultyUsage',
            'periodLabel', // Ganti monthName jadi periodLabel yang lebih dinamis
            'years',
            'months',
            'days',
            'selectedYear',
            'selectedMonth',
            'selectedDay'
        ));
    }
}