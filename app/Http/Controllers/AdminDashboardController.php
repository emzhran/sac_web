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
        $currentYear = now()->year;
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', now()->month);
        $selectedDay = $request->input('day');

        $years = range($currentYear - 4, $currentYear + 1);
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->translatedFormat('F');
        }

        $days = range(1, 31);

        if ($selectedDay) {
            $dateObj = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay);
            $periodLabel = $dateObj->translatedFormat('d F Y');
        } else {
            $dateObj = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $periodLabel = $dateObj->translatedFormat('F Y');
        }

        $applyDateFilter = function ($query, $column = 'updated_at') use ($selectedYear, $selectedMonth, $selectedDay) {
            $query->whereYear($column, $selectedYear)
                ->whereMonth($column, $selectedMonth);

            if ($selectedDay) {
                $query->whereDay($column, $selectedDay);
            }
            return $query;
        };

        $successStatus = 'approved';
        $pendingStatus = 'pending';

        $bookingQuery = Booking::where('status', $successStatus);
        $totalBookings = $applyDateFilter($bookingQuery, 'updated_at')->count();

        $pendingQuery = Booking::where('status', $pendingStatus);
        $pendingBookings = $applyDateFilter($pendingQuery, 'updated_at')->count();

        $usageStatsQuery = Booking::select('lapangan_id', DB::raw('COUNT(*) as bookings_count'))
            ->where('status', $successStatus);

        $usageStats = $applyDateFilter($usageStatsQuery, 'updated_at')
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
            $stat = $usageStats->where('lapangan_id', $lapangan->id)->first();
            $count = $stat ? $stat->bookings_count : 0;

            $displayName = strtok($lapangan->nama, ' ');

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

        $facultyQuery = Booking::select('users.fakultas', DB::raw('COUNT(bookings.id) as bookings'))
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.status', $successStatus)
            ->whereNotNull('users.fakultas');

        $applyDateFilter($facultyQuery, 'bookings.created_at');

        $facultyUsageRaw = $facultyQuery->groupBy('users.fakultas')->get();

        $allFaculties = [
            'Teknik',
            'Agama Islam',
            'Kedokteran & Ilmu Kesehatan',
            'Kedokteran Gigi',
            'Pertanian',
            'Ilmu Sosial Politik',
            'Ekonomi & Bisnis',
            'Pendidikan Bahasa',
            'Hukum',
            'Psikologi',
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
            'facultyUsage',
            'periodLabel',
            'years',
            'months',
            'days',
            'selectedYear',
            'selectedMonth',
            'selectedDay'
        ));
    }
}
