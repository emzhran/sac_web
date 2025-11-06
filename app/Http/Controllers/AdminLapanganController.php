<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class AdminLapanganController extends Controller
{
    public function futsalIndex(Request $request)
    {
        $status = $request->get('status', 'pending');
        $futsalBookings = collect(); 
        $dbError = null; 

        try {
            $query = DB::table('futsals');

            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            $futsalBookings = $query->orderBy('jadwal', 'asc')->get();

        } catch (QueryException $e) {
            $dbError = 'Pastikan tabel futsals sudah ada dan kolom yang dibutuhkan tersedia.';
        }

        return view('admin.lapangan.futsal', compact('futsalBookings', 'dbError'));
    }
    
    public function badmintonIndex(Request $request)
    {
        $status = $request->get('status', 'pending');
        $badmintonBookings = collect(); 
        $dbError = null; 

        try {
            $query = DB::table('badmintons');

            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            $badmintonBookings = $query->orderBy('jadwal', 'asc')->get();

        } catch (QueryException $e) {
            $dbError = 'Pastikan tabel badmintons sudah ada dan kolom yang dibutuhkan tersedia.';
        }

        return view('admin.lapangan.badminton', compact('badmintonBookings', 'dbError')); 
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $statusToUpdate = $request->route('status'); 

        $validStatuses = ['approved', 'rejected'];
        
        if (!in_array($statusToUpdate, $validStatuses)) {
            return Redirect::back()->withErrors(['error' => 'Status pembaruan tidak valid.']);
        }

        $validTables = ['futsals', 'badmintons', 'volis', 'baskets'];
        if (!in_array($type, $validTables)) {
            return Redirect::back()->withErrors(['error' => 'Jenis lapangan tidak valid.']);
        }
        
        try {
            $booking = DB::table($type)->where('id', $id)->first();
            
            if (!$booking) {
                return Redirect::back()->with('status', 'Pemesanan tidak ditemukan.');
            }

            if ($booking->status !== 'pending') {
                 return Redirect::back()->with('status', 'Pemesanan ini sudah ' . ucfirst($booking->status) . '.');
            }

            $updated = DB::table($type)
                ->where('id', $id)
                ->update(['status' => $statusToUpdate]);
            
            if ($updated) {
                $message = $statusToUpdate === 'approved' 
                           ? 'Pemesanan ' . $booking->nama . ' berhasil disetujui (Approved).' 
                           : 'Pemesanan ' . $booking->nama . ' berhasil ditolak (Rejected).';
                           
                return Redirect::back()->with('status', $message);
            } else {
                return Redirect::back()->with('status', 'Gagal memperbarui status pemesanan.');
            }

        } catch (QueryException $e) {
            return Redirect::back()->withErrors(['db_error' => 'Gagal memproses data: Terjadi kesalahan database saat update status.']);
        }
    }
}