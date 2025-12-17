<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Sport Centre UMY</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>

<body class="antialiased bg-white">

    <div class="flex flex-col md:flex-row min-h-screen">

        <div class="relative overflow-hidden flex flex-col justify-between p-8 md:p-12 md:w-1/2 text-white bg-cover bg-center bg-no-repeat"
             style="background-image: url('{{ asset('asset/images/login.jpeg') }}');">

            {{-- UBAH: Warna overlay menjadi Biru Tua --}}
            <div class="absolute inset-0 bg-blue-900/60 z-0"></div>

            <div class="z-10 relative h-full flex flex-col justify-between">
                <div class="mb-6 bg-white/20 w-fit p-2 rounded-lg backdrop-blur-sm">
                    <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Logo SAC"
                         class="h-12 w-auto">
                </div>

                <div class="mt-auto mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Sign in to</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold mb-6">Sport Centre UMY</h2>
                    <p class="max-w-md opacity-90 leading-relaxed">
                        Selamat datang di portal layanan aktivitas olahraga mahasiswa. Silakan masuk untuk mengakses layanan peminjaman lapangan.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12 md:w-1/2 flex items-center justify-center bg-white relative">
            <div class="w-full max-w-md">

                <div class="absolute top-8 right-8 text-right">
                    <span class="text-gray-500 text-sm">Belum punya akun?</span>
                    {{-- UBAH: Warna text link menjadi Biru --}}
                    <a href="{{ route('register') }}"
                       class="text-blue-600 font-medium hover:underline text-sm ml-1">Daftar</a>
                </div>

                <div class="mb-10 mt-8 md:mt-0">
                    <p class="text-gray-600 mb-2 font-medium">
                        Welcome to <span class="text-blue-600 font-bold">SPORT CENTRE UMY</span>
                    </p>
                    <h1 class="text-4xl font-bold text-gray-900">Sign in</h1>
                </div>

                @if (session('status'))
                    {{-- UBAH: Warna alert status menjadi Biru --}}
                    <div class="mb-4 p-4 rounded-md bg-blue-50 border border-blue-200 text-blue-600 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        {{-- UBAH: Focus ring menjadi Biru --}}
                        <x-text-input id="email"
                            class="w-full h-12 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            type="email" name="email" :value="old('email')" placeholder="username@umy.ac.id" required
                            autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="space-y-2" x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>

                        <div class="relative">
                            {{-- UBAH: Focus ring menjadi Biru --}}
                            <x-text-input id="password"
                                class="w-full h-12 px-4 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                ::type="show ? 'text' : 'password'" name="password" placeholder="Enter your password"
                                required />

                            {{-- UBAH: Hover icon mata menjadi Biru --}}
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">

                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex justify-between items-center mt-1">
                            <x-input-error :messages="$errors->get('password')" />

                            @if (Route::has('password.request'))
                                {{-- UBAH: Warna link forgot password menjadi Biru --}}
                                <a href="{{ route('password.request') }}"
                                    class="text-blue-600 text-sm hover:underline ml-auto">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center">
                        {{-- UBAH: Warna checkbox menjadi Biru --}}
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            name="remember">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>

                    {{-- UBAH: Tombol utama menjadi Biru dan hover Biru Tua --}}
                    <button type="submit"
                        class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md transition duration-200 shadow-sm">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>