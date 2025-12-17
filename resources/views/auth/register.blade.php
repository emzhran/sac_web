<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Sport Centre UMY</title>

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

            <div class="absolute inset-0 bg-blue-900/60 z-0"></div>

            <div class="z-10 relative h-full flex flex-col justify-between">
                <div class="mb-6 bg-white/20 w-fit p-2 rounded-lg backdrop-blur-sm">
                    <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Logo"
                        class="h-12 w-auto">
                </div>

                <div class="mt-auto mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Daftar Akun Terlebih Dahulu</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold mb-6">Create Your Account</h2>
                    <p class="max-w-md opacity-90 leading-relaxed">
                        Bergabunglah dengan Sport Centre UMY. Daftarkan diri Anda untuk mulai meminjam fasilitas
                        dan mengakses layanan kemahasiswaan.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12 md:w-1/2 flex items-center justify-center bg-white relative overflow-y-auto">
            <div class="w-full max-w-lg">

                <div class="absolute top-8 right-8 text-right">
                    <span class="text-gray-500 text-sm">Sudah punya akun?</span>
                    <a href="{{ route('login') }}"
                        class="text-blue-600 font-medium hover:underline text-sm ml-1">Login</a>
                </div>

                <div class="mb-8 mt-8 md:mt-0">
                    <p class="text-gray-600 mb-2 font-medium">
                        Welcome to <span class="text-blue-600 font-bold">SAC UMY</span>
                    </p>
                    <h1 class="text-4xl font-bold text-gray-900">Registrasi Akun</h1>
                </div>

                <form method="POST" action="{{ route('register') }}" novalidate class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700" />
                        <x-text-input id="name"
                            class="block mt-1 w-full h-12 border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                            placeholder="Nama Lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="nim" :value="__('NIM')" class="text-gray-700" />
                        <x-text-input id="nim"
                            class="block mt-1 w-full h-12 border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md"
                            type="text" name="nim" :value="old('nim')" required autocomplete="nim" inputmode="numeric"
                            pattern="[0-9]*" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            placeholder="Nomor Induk Mahasiswa" />
                        <x-input-error :messages="$errors->get('nim')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="fakultas" :value="__('Fakultas')" class="text-gray-700" />
                            <select id="fakultas" name="fakultas" required
                                class="block mt-1 w-full h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-gray-700">
                                <option value="" disabled selected>Pilih Fakultas</option>
                                <option value="Teknik" @selected(old('fakultas') == 'Teknik')>Teknik</option>
                                <option value="Agama Islam" @selected(old('fakultas') == 'Agama Islam')>Agama Islam
                                </option>
                                <option value="Kedokteran & Ilmu Kesehatan" @selected(old('fakultas') == 'Kedokteran & Ilmu Kesehatan')>Kedokteran & Ilmu Kesehatan</option>
                                <option value="Kedokteran Gigi" @selected(old('fakultas') == 'Kedokteran Gigi')>Kedokteran
                                    Gigi</option>
                                <option value="Pertanian" @selected(old('fakultas') == 'Pertanian')>Pertanian</option>
                                <option value="Ilmu Sosial Politik" @selected(old('fakultas') == 'Ilmu Sosial Politik')>
                                    Ilmu Sosial Politik</option>
                                <option value="Ekonomi & Bisnis" @selected(old('fakultas') == 'Ekonomi & Bisnis')>Ekonomi
                                    & Bisnis</option>
                                <option value="Pendidikan Bahasa" @selected(old('fakultas') == 'Pendidikan Bahasa')>
                                    Pendidikan Bahasa</option>
                                <option value="Hukum" @selected(old('fakultas') == 'Hukum')>Hukum</option>
                                <option value="Psikologi" @selected(old('fakultas') == 'Psikologi')>Psikologi</option>
                            </select>
                            <x-input-error :messages="$errors->get('fakultas')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="prodi" :value="__('Program Studi')" class="text-gray-700" />
                            <select id="prodi" name="prodi" required disabled
                                class="block mt-1 w-full h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-gray-700 bg-gray-50">
                                <option value="" disabled selected>Pilih Program Studi</option>
                            </select>
                            <x-input-error :messages="$errors->get('prodi')" class="mt-1" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                        <x-text-input id="email"
                            class="block mt-1 w-full h-12 border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md"
                            type="email" name="email" :value="old('email')" required autocomplete="username"
                            placeholder="user@gmail.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                        <div class="relative mt-1">
                            <x-text-input id="password"
                                class="block w-full h-12 pr-10 border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md transition"
                                ::type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                placeholder="Min. 8 Karakter" />

                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        <p class="mt-1 text-xs text-gray-500">
                            Gunakan angka, huruf kapital, dan karakter khusus (@#$%&*).
                        </p>
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                            class="text-gray-700" />
                        <div class="relative mt-1">
                            <x-text-input id="password_confirmation"
                                class="block w-full h-12 pr-10 border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md transition"
                                ::type="show ? 'text' : 'password'" name="password_confirmation" required
                                autocomplete="new-password" placeholder="Ulangi Password" />

                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md transition duration-200 shadow-sm text-lg">
                            Daftar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fakultasSelect = document.getElementById('fakultas');
            const prodiSelect = document.getElementById('prodi');
            const oldProdi = "{{ old('prodi') }}";

            const prodiData = {
                "Teknik": ["Teknik Sipil", "Teknik Mesin", "Teknik Elektro", "Teknologi Informasi", "Teknologi Elektro-Medis", "Teknologi Rekayasa Otomotif"],
                "Agama Islam": ["Komunikasi dan Penyiaran Islam", "Pendidikan Agama Islam", "Ekonomi Syariah"],
                "Kedokteran & Ilmu Kesehatan": ["Kedokteran", "Pendidikan Profesi Dokter", "Pendidikan Profesi Ners", "Farmasi", "Apoteker", "Keperawatan"],
                "Kedokteran Gigi": ["Kedokteran Gigi", "Profesi Dokter Gigi"],
                "Pertanian": ["Agroteknologi", "Agribisnis"],
                "Ilmu Sosial Politik": ["Hubungan Internasional", "Ilmu Komunikasi", "Ilmu Pemerintahan", "International Program of International Relations (IPIREL)", "International Program of Government Affairs and Administration (IGOV)", "International Program of Communication Studies (IP-COS)"],
                "Ekonomi & Bisnis": ["Manajemen", "Akuntansi", "Ekonomi", "International Program of Management and Business (IMaBs)", "International Program of Accounting (IPAcc)", "International Undergraduate Program for Islamic Economics and Finance (IPIEF)", "Magister Manajemen", "Magister Akuntansi", "Magister Ekonomi", "Doktor Manajemen"],
                "Pendidikan Bahasa": ["Pendidikan Bahasa Inggris", "Pendidikan Bahasa Arab", "Pendidikan Bahasa Jepang"],
                "Hukum": ["Hukum", "Internasional Ilmu Hukum (IPOLS)"],
                "Psikologi": ["Psikologi"]
            };

            function updateProdiDropdown(selectedFakultas) {
                prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Program Studi</option>';

                if (selectedFakultas && prodiData[selectedFakultas]) {
                    prodiSelect.disabled = false;
                    prodiSelect.classList.remove('bg-gray-50');

                    const prodiList = prodiData[selectedFakultas];
                    prodiList.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi;
                        option.textContent = prodi;
                        if (oldProdi === prodi) {
                            option.selected = true;
                        }
                        prodiSelect.appendChild(option);
                    });
                } else {
                    prodiSelect.disabled = true;
                    prodiSelect.classList.add('bg-gray-50');
                }
            }

            fakultasSelect.addEventListener('change', function () {
                updateProdiDropdown(this.value);
            });

            const initialFakultas = fakultasSelect.value;
            if (initialFakultas) {
                updateProdiDropdown(initialFakultas);
            }
        });
    </script>
</body>

</html>