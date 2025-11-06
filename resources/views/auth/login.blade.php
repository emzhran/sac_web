<x-guest-layout class="bg-gray-100 min-h-screen flex items-center justify-end px-10">
    <div class="relative flex w-full max-w-7xl bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- LOGO -->
        <div class="w-1/2 bg-pink-100 flex items-center justify-center">
            <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Logo" class="w-80 h-auto">
        </div>

        <!-- FORM  -->
        <div class="w-1/2 p-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Masuk</h2>
            <p class="text-lg text-gray-500 mb-8">Masukkan email dan password Anda</p>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <x-input-label for="email" :value="'Email'" />
                    <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <x-input-label for="password" :value="'Password'" />
                    <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-6 flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500" name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat akun saya</label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-6 rounded-md shadow-md text-lg">
                        MASUK
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-pink-600 hover:text-pink-700 underline">
                            Daftar Sekarang.
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>