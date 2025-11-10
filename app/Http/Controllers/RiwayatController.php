<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userName = Auth::user()->name;

        $riwayats = Booking::where('nama_pemesan', $userName)
            ->with(['lapangan', 'jadwal'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('riwayat.riwayat', compact('riwayats'));
    }
}
