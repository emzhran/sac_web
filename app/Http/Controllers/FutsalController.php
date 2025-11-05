<?php

namespace App\Http\Controllers;

use App\Models\Futsal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FutsalController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'jadwal' => 'required|date|after_or_equal:now',  
                'status' => 'required|string|in:pending', 
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i',
            ]);

            $jamMulaiTime = strtotime($validated['jam_mulai']);
            $jamSelesaiTime = strtotime($validated['jam_selesai']);
            if ($jamSelesaiTime <= $jamMulaiTime) {
                throw ValidationException::withMessages([
                    'jam_selesai' => ['Jam selesai harus setelah jam mulai.'],
                ]);
            }

            $tanggal = Carbon::parse($validated['jadwal'])->toDateString();

            $isConflict = Futsal::whereDate('jadwal', $tanggal)
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<', $validated['jam_selesai'])
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                    });
                })
                ->exists();

            if ($isConflict) {
                return response()->json([
                    'message' => 'Tidak dapat membooking lapangan karena lapangan sudah dibooking.',
                ], 409);
            }

            $validated['nama'] = Auth::user()->name;

            Futsal::create($validated);

            return response()->json([
                'message' => 'Pemesanan lapangan futsal berhasil diajukan!'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
