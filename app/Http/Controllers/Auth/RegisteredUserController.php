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
            'nim' => ['required', 'regex:/^[0-9]+$/', 'unique:'.User::class, 'digits_between:5,11'],
            'fakultas' => ['required', 'string', 'max:255'],
            'prodi' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@mail.umy.ac.id'],
            'password' => [
                'required', 
                'confirmed', 
                Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(), 
            ],

        ], [
            'nim.required' => 'Kolom Nomor Induk Mahasiswa (NIM) wajib diisi.',
            'nim.integer' => 'Nomor Induk Mahasiswa (NIM) harus berupa angka.',
            'nim.digits_between' => 'Nomor Induk Mahasiswa (NIM) harus berupa angka antara :min dan :max digit.',
            'nim.unique' => 'Nomor Induk Mahasiswa (NIM) ini sudah terdaftar.',
            'name.required' => 'Kolom Nama Lengkap wajib diisi.',
            'fakultas.required' => 'Kolom Fakultas wajib diisi.',
            'prodi.required' => 'Kolom Program Studi wajib diisi.',
            'email.required' => 'Kolom Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'email.ends_with' => 'Email yang digunakan harus menggunakan domain UMY (contoh: user@mail.umy.ac.id).',
            'password.min' => 'Kata sandi minimal harus :min karakter.',
            'password.mixed' => 'Kata sandi harus mengandung minimal satu huruf kapital dan satu huruf kecil.',
            'password.numbers' => 'Kata sandi harus mengandung minimal satu angka.',
            'password.symbols' => 'Kata sandi harus mengandung minimal satu karakter khusus.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.required' => 'Kolom kata sandi wajib diisi.',


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

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}