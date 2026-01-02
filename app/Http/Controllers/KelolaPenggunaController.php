<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaPenggunaController extends Controller
{
    private function getFakultasData()
    {
        return [
            "Teknik" => [
                "Teknik Sipil", "Teknik Mesin", "Teknik Elektro", "Teknologi Informasi", 
                "Teknologi Elektro-Medis", "Teknologi Rekayasa Otomotif"
            ],
            "Agama Islam" => [
                "Komunikasi dan Penyiaran Islam", "Pendidikan Agama Islam", "Ekonomi Syariah"
            ],
            "Kedokteran & Ilmu Kesehatan" => [
                "Kedokteran", "Pendidikan Profesi Dokter", "Pendidikan Profesi Ners", 
                "Farmasi", "Apoteker", "Keperawatan"
            ],
            "Kedokteran Gigi" => [
                "Kedokteran Gigi", "Profesi Dokter Gigi"
            ],
            "Pertanian" => [
                "Agroteknologi", "Agribisnis"
            ],
            "Ilmu Sosial Politik" => [
                "Hubungan Internasional", "Ilmu Komunikasi", "Ilmu Pemerintahan", 
                "International Program of International Relations (IPIREL)", 
                "International Program of Government Affairs and Administration (IGOV)", 
                "International Program of Communication Studies (IP-COS)"
            ],
            "Ekonomi & Bisnis" => [
                "Manajemen", "Akuntansi", "Ekonomi", 
                "International Program of Management and Business (IMaBs)", 
                "International Program of Accounting (IPAcc)", 
                "International Undergraduate Program for Islamic Economics and Finance (IPIEF)", 
                "Magister Manajemen", "Magister Akuntansi", "Magister Ekonomi", "Doktor Manajemen"
            ],
            "Pendidikan Bahasa" => [
                "Pendidikan Bahasa Inggris", "Pendidikan Bahasa Arab", "Pendidikan Bahasa Jepang"
            ],
            "Hukum" => [
                "Hukum", "Internasional Ilmu Hukum (IPOLS)"
            ],
            "Psikologi" => [
                "Psikologi"
            ]
        ];
    }

    public function index(Request $request)
    {
        $fakultasData = $this->getFakultasData();
        $query = User::whereNotIn('role', ['admin', 'guest']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('nim')) $query->where('nim', 'like', "%{$request->nim}%");
        if ($request->filled('fakultas')) $query->where('fakultas', $request->fakultas);
        if ($request->filled('prodi')) $query->where('prodi', $request->prodi);

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.kelolapengguna.index', compact('users', 'fakultasData'));
    }
    public function edit(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Admin tidak dapat diedit di menu ini.');
        }

        $fakultasData = $this->getFakultasData();
        return view('admin.kelolapengguna.edit', compact('user', 'fakultasData'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nim' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'fakultas' => ['nullable', 'string'],
            'prodi' => ['nullable', 'string'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa menghapus admin.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}