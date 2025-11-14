<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'regex:/^[0-9]+$/', 'unique:'.User::class, 'digits:11'],
            'fakultas' => ['required', 'string', 'max:255'],
            'prodi' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@gmail.com'],
            'password' => [
                'required', 
                'confirmed', 
                Rules\Password::min(8)->mixedCase()->numbers()->symbols(), 
            ],
        
        ], [ 
            'nim.required' => 'Nomor Induk Mahasiswa (NIM) wajib diisi.',
            'nim.integer' => 'Nomor Induk Mahasiswa (NIM) harus berupa angka.',
            'nim.digits' => 'Nomor Induk Mahasiswa (NIM) harus berupa angka 11 digit.',
            'nim.unique' => 'Nomor Induk Mahasiswa (NIM) ini sudah terdaftar.',
            'name.required' => 'Nama Lengkap wajib diisi.',
            'fakultas.required' => 'Fakultas wajib dipilih.',
            'prodi.required' => 'Program Studi wajib dipilih.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Kata sandi minimal harus :min karakter.',
            'password.mixed_case' => 'Kata sandi harus mengandung minimal satu huruf kapital dan satu huruf kecil.',
            'password.numbers' => 'Kata sandi harus mengandung minimal satu angka.',
            'password.symbols' => 'Kata sandi harus mengandung minimal satu karakter khusus.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.required' => 'Kata sandi wajib diisi.',
            'nim.regex' => 'Nomor Induk Mahasiswa (NIM) harus berupa angka.', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('login')
            ->with('status', 'Registrasi berhasil! Silakan cek email anda untuk verifikasi akun.');
    }
}