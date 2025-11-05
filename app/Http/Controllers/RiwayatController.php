<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Badminton;
use App\Models\Futsal;
use App\Models\Voli;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userName = Auth::user()->name;

        $riwayatBadminton = Badminton::where('nama', $userName)
            ->select('id', 'nama', 'jadwal', 'jam_mulai', 'jam_selesai', 'status')
            ->get()
            ->map(function ($item) {
                $item->lapangan = 'Badminton';
                return $item;
            });

        $riwayatFutsal = Futsal::where('nama', $userName)
            ->select('id', 'nama', 'jadwal', 'jam_mulai', 'jam_selesai', 'status')
            ->get()
            ->map(function ($item) {
                $item->lapangan = 'Futsal';
                return $item;
            });

        $riwayatVoli = Voli::where('nama', $userName)
            ->select('id', 'nama', 'jadwal', 'jam_mulai', 'jam_selesai', 'status')
            ->get()
            ->map(function ($item) {
                $item->lapangan = 'Voli';
                return $item;
            });

        $allRiwayats = $riwayatBadminton
            ->merge($riwayatFutsal)
            ->merge($riwayatVoli)
            ->sortByDesc('jadwal')
            ->values(); 

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allRiwayats->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedRiwayats = new LengthAwarePaginator(
            $currentItems,
            $allRiwayats->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('riwayat.riwayat', [
            'riwayats' => $paginatedRiwayats,
        ]);
    }
}
