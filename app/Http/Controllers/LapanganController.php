<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LapanganController extends Controller
{
    public function userIndex()
    {
        $lapangans = Lapangan::all();

        return view('booking.index', compact('lapangans')); 
    }
    
    public function index()
    {
        $lapangans = Lapangan::all();

        return view('admin.lapangan.index', compact('lapangans'));
    }


    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:lapangans,nama', 
        ]);

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan baru berhasil ditambahkan!');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lapangans')->ignore($lapangan->id), 
            ],
        ]);

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil dihapus!');
    }
}