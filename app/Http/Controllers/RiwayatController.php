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
            ->with(['lapangan', 'jadwals'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('riwayat.riwayat', compact('riwayats'));
    }
    public function adminIndex()
    {
        $riwayats = Booking::with(['lapangan', 'jadwals'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.riwayat.index', compact('riwayats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!auth()->user()->is_admin && $booking->nama_pemesan !== auth()->user()->name) {
            abort(403, 'Unauthorized action.');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Riwayat berhasil dihapus');
    }
}