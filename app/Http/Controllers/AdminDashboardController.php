<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lapangan; 
use App\Models\Booking; 
use App\Models\Jadwal;   
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $successStatus = 'approved';
        $pendingStatus = 'pending';
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();
        $startOfMonthTime = now()->startOfMonth();
        $endOfMonthTime = now()->endOfMonth();
        $totalBookings = Booking::where('status', $successStatus)
        ->whereBetween('updated_at', [$startOfMonthTime, $endOfMonthTime])

            ->count();
        $pendingBookings = Booking::where('status', $pendingStatus)
        ->whereBetween('updated_at', [$startOfMonthTime, $endOfMonthTime])

            ->count();
        $usageStats = Booking::select('lapangan_id', DB::raw('COUNT(*) as bookings_count'))
            ->where('status', $successStatus)
            ->whereBetween('updated_at', [$startOfMonthTime, $endOfMonthTime])

            ->groupBy('lapangan_id')
            ->get();
        
        $lapangans = Lapangan::all();
        $fieldDetails = [];
        $maxBookings = 0;
        $mostUsedField = 'N/A';

        $colors = ['red', 'yellow', 'green', 'purple', 'indigo', 'pink'];
        $colorIndex = 0;

        $totalUsage = $usageStats->sum('bookings_count');
        if ($totalUsage == 0) $totalUsage = 1;

        foreach ($lapangans as $lapangan) {
            $count = $usageStats->where('lapangan_id', $lapangan->id)->first()->bookings_count ?? 0;
            
            $displayName = strtok($lapangan->nama, ' ');

            $fieldDetails[] = [
                'name' => $displayName,
                'bookings' => $count,
                'percentage' => round(($count / $totalUsage) * 100, 1), // ← tambahan
                'color' => $colors[$colorIndex % count($colors)],
            ];
            
            $colorIndex++;
            
            if ($count > $maxBookings) {
                $maxBookings = $count;
                $mostUsedField = $displayName;
            }
        }
        
        
        $facultyUsageRaw = Booking::select('users.fakultas', DB::raw('COUNT(bookings.id) as bookings'))
            ->join('users', 'bookings.nama_pemesan', '=', 'users.name') 
            ->where('bookings.status', $successStatus)
            ->whereBetween('bookings.created_at', [$startOfMonthTime, $endOfMonthTime])
            ->whereNotNull('users.fakultas')
            ->groupBy('users.fakultas')
            ->get();

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

        usort($facultyUsage, fn($a, $b) => $b['bookings'] <=> $a['bookings']);

        return view('dashboard-admin', compact(
            'totalBookings',
            'pendingBookings',
            'mostUsedField',
            'fieldDetails',
            'facultyUsage'
        ));
    }
}