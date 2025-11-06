<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Futsal;
use App\Models\Badminton;
use App\Models\Voli;
use App\Models\Basket;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $successStatus = 'approved';
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $futsalBookings = Futsal::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', $successStatus)
            ->count();

        $badmintonBookings = Badminton::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', $successStatus)
            ->count();

        $voliBookings = Voli::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', $successStatus)
            ->count();

        $basketBookings = Basket::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', $successStatus)
            ->count();

        $allBookings = [
            'Futsal' => $futsalBookings,
            'Badminton' => $badmintonBookings,
            'Voli' => $voliBookings,
            'Basket' => $basketBookings,
        ];

        $totalBookings = array_sum($allBookings);

        $pendingFutsal = Futsal::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', 'pending')
            ->count();

        $pendingBadminton = Badminton::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', 'pending')
            ->count();

        $pendingVoli = Voli::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', 'pending')
            ->count();

        $pendingBasket = Basket::whereDate('jadwal', '>=', $startOfMonth)
            ->whereDate('jadwal', '<=', $endOfMonth)
            ->where('status', 'pending')
            ->count();

        $pendingBookings = $pendingFutsal + $pendingBadminton + $pendingVoli + $pendingBasket;

        $mostUsedField = $totalBookings > 0
            ? array_search(max($allBookings), $allBookings)
            : 'N/A';

        $fieldDetails = [
            ['name' => 'Futsal', 'bookings' => $futsalBookings, 'color' => 'red'],
            ['name' => 'Badminton', 'bookings' => $badmintonBookings, 'color' => 'yellow'],
            ['name' => 'Voli', 'bookings' => $voliBookings, 'color' => 'green'],
            ['name' => 'Basket', 'bookings' => $basketBookings, 'color' => 'purple'],
        ];

        $allFaculties = [
            'Teknik', 'Agama Islam', 'Kedokteran & Ilmu Kesehatan', 'Kedokteran Gigi',
            'Pertanian', 'Ilmu Sosial Politik', 'Ekonomi & Bisnis', 'Pendidikan Bahasa',
            'Hukum', 'Psikologi',
        ];

        $models = [new Futsal(), new Badminton(), new Voli(), new Basket()];
        $facultyCounts = collect();
        $foreignKey = 'id';

        foreach ($models as $model) {
            $tableName = $model->getTable();

            $usage = DB::table($tableName)
                ->select('users.fakultas', DB::raw('COUNT(*) as bookings'))
                ->leftJoin('users', 'users.id', '=', $tableName . '.' . $foreignKey)
                ->whereDate($tableName . '.jadwal', '>=', $startOfMonth)
                ->whereDate($tableName . '.jadwal', '<=', $endOfMonth)
                ->where($tableName . '.status', $successStatus)
                ->groupBy('users.fakultas')
                ->get();

            $facultyCounts = $facultyCounts->concat($usage->pluck('bookings', 'fakultas'));
        }

        $facultyUsage = [];
        $aggregatedCounts = $facultyCounts->filter(fn($count, $fakultas) => $fakultas !== null)->reduce(function ($carry, $count, $fakultas) {
            $carry[$fakultas] = ($carry[$fakultas] ?? 0) + $count;
            return $carry;
        }, []);

        foreach ($allFaculties as $facultyName) {
            $facultyUsage[] = [
                'name' => $facultyName,
                'bookings' => $aggregatedCounts[$facultyName] ?? 0,
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
