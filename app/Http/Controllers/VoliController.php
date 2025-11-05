<?php

namespace App\Http\Controllers;

use App\Models\Voli;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VoliController extends Controller
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

            if (substr($validated['jam_mulai'], 3, 2) !== '00' || substr($validated['jam_selesai'], 3, 2) !== '00') {
                throw ValidationException::withMessages([
                    'jam_mulai' => ['Jam harus dimulai dan diakhiri pada menit 00 (contoh: 10:00, 11:00).'],
                ]);
            }

            $jamMulaiTime = strtotime($validated['jam_mulai']);
            $jamSelesaiTime = strtotime($validated['jam_selesai']);
            if ($jamSelesaiTime <= $jamMulaiTime) {
                throw ValidationException::withMessages([
                    'jam_selesai' => ['Jam selesai harus setelah jam mulai.'],
                ]);
            }

            $tanggal = Carbon::parse($validated['jadwal'])->toDateString();

            $isConflict = Voli::whereDate('jadwal', $tanggal)
                ->where(function ($query) use ($validated) {
                    $query->where(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<', $validated['jam_selesai'])
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                    });
                })
                ->exists();

            if ($isConflict) {
                return response()->json([
                    'message' => 'Tidak dapat membooking lapangan karena lapangan sudah dibooking pada jam tersebut.',
                ], 409);
            }

            $validated['nama'] = Auth::user()->name;
            Voli::create($validated);

            return response()->json(['message' => 'Pemesanan lapangan voli berhasil diajukan!'], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
