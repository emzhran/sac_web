<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $riwayats = Booking::where('user_id', Auth::id())
            ->with(['lapangan', 'jadwals'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('riwayat.riwayat', compact('riwayats'));
    }

    public function adminIndex()
    {
        $riwayats = Booking::with(['lapangan', 'jadwals', 'user'])
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

        if (!auth()->user()->is_admin && $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Riwayat berhasil dihapus');
    }

    public function cetakPdf($id)
    {
        $booking = Booking::with(['user', 'lapangan', 'jadwals'])->findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pdf ini.');
        }

        if ($booking->status !== 'approved') {
            return redirect()->back()->with('error', 'Booking belum disetujui, tiket tidak tersedia.');
        }

        $pdf = Pdf::loadView('pdf.pdf_booking', compact('booking'));

        return $pdf->download('PDF Booking SAC' . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(new BookingExport($startDate, $endDate), 'Laporan_Booking_' . $startDate . '_sd_' . $endDate . '.xlsx');
    }

    public function confirmPresence(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'approved') {
            return redirect()->back()->with('error', 'Booking belum disetujui.');
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        $umyLat = -7.811345;
        $umyLng = 110.320745;

        $userLat = $request->latitude;
        $userLng = $request->longitude;

        $theta = $userLng - $umyLng;
        $dist = sin(deg2rad($userLat)) * sin(deg2rad($umyLat)) +  cos(deg2rad($userLat)) * cos(deg2rad($umyLat)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = $miles * 1.609344;
        if ($km > 0.5) {
            return redirect()->back()->with('error', 'Gagal konfirmasi! Lokasi Anda terdeteksi sejauh ' . number_format($km, 2) . ' KM dari area kampus (Maks 0.5 KM).');
        }
        $booking->update([
            'confirmed_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Kehadiran berhasil dikonfirmasi! Selamat berolahraga.');
    }

    public function show($id)
    {

        $booking = Booking::with(['lapangan', 'jadwals', 'user'])->findOrFail($id);

        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('riwayat.show', compact('booking'));
    }
}