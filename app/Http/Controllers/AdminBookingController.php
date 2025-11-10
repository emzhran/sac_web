<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\QueryException;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $query = Booking::with('lapangan', 'jadwals');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10); 
        return view('admin.booking.index', compact('bookings', 'status'));
    }

    public function updateStatus(Booking $booking, $status)
    {
        $validStatuses = ['approved', 'rejected'];

        if (!in_array($status, $validStatuses)) {
            return Redirect::back()->withErrors(['error' => 'Status pembaruan tidak valid.']);
        }

        if ($booking->status !== 'pending') {
            return Redirect::back()->with('status', 'Pemesanan ini sudah ' . ucfirst($booking->status) . '.');
        }

        try {
            $booking->status = $status;
            $booking->save();

            $message = $status === 'approved'
                ? 'Pemesanan ' . $booking->nama_pemesan . ' berhasil disetujui (Approved).'
                : 'Pemesanan ' . $booking->nama_pemesan . ' berhasil ditolak (Rejected).';

            return Redirect::back()->with('status', $message);
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['db_error' => 'Gagal memproses data: Terjadi kesalahan database saat update status.']);
        }
    }
}