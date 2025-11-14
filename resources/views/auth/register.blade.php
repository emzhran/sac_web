<x-guest-layout class="bg-gray-100 min-h-screen flex items-center justify-end px-10">
    <div class="relative flex w-full max-w-7xl bg-white rounded-xl shadow-lg overflow-hidden">

        <div class="w-1/2 bg-pink-100 flex items-center justify-center">
            <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Logo" class="w-80 h-auto">
        </div>

        <div class="w-1/2 p-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Registrasi Akun</h2>
            <p class="text-lg text-gray-500 mb-8">Isi data Anda untuk membuat akun baru</p>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="nim" :value="__('NIM (Nomor Induk Mahasiswa)')" />
                    <x-text-input
                        id="nim"
                        class="block mt-2 w-full"
                        type="text"
                        name="nim"
                        :value="old('nim')"
                        required
                        autocomplete="nim"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="11"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                    <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                </div>


                <div class="mb-4">
                    <x-input-label for="fakultas" :value="__('Fakultas')" />
                    <select id="fakultas" name="fakultas" required
                        class="block mt-2 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-md shadow-sm">
                        <option value="" disabled selected>Pilih Fakultas</option>
                        <option value="Teknik" @selected(old('fakultas')=='Teknik' )>Teknik</option>
                        <option value="Agama Islam" @selected(old('fakultas')=='Agama Islam' )>Agama Islam</option>
                        <option value="Kedokteran & Ilmu Kesehatan" @selected(old('fakultas')=='Kedokteran & Ilmu Kesehatan' )>Kedokteran & Ilmu Kesehatan</option>
                        <option value="Kedokteran Gigi" @selected(old('fakultas')=='Kedokteran Gigi' )>Kedokteran Gigi</option>
                        <option value="Pertanian" @selected(old('fakultas')=='Pertanian' )>Pertanian</option>
                        <option value="Ilmu Sosial Politik" @selected(old('fakultas')=='Ilmu Sosial Politik' )>Ilmu Sosial Politik</option>
                        <option value="Ekonomi & Bisnis" @selected(old('fakultas')=='Ekonomi & Bisnis' )>Ekonomi & Bisnis</option>
                        <option value="Pendidikan Bahasa" @selected(old('fakultas')=='Pendidikan Bahasa' )>Pendidikan Bahasa</option>
                        <option value="Hukum" @selected(old('fakultas')=='Hukum' )>Hukum</option>
                        <option value="Psikologi" @selected(old('fakultas')=='Psikologi' )>Psikologi</option>
                    </select>
                    <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="prodi" :value="__('Program Studi')" />
                    <select id="prodi" name="prodi" required disabled
                        class="block mt-2 w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-md shadow-sm">
                        <option value="" disabled selected>Pilih Program Studi</option>
                    </select>
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="username"
                        placeholder="user@gmail.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <p class="mt-1 text-xs text-pink-600">
                        Minimal panjang password 8 karakter, mengandung:
                        <span class="font-semibold">Angka, Huruf Kapital, dan Karakter Khusus (@#$%&*)</span>.
                    </p>
                </div>

                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                    <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-6 rounded-md shadow-md text-lg">
                        {{ __('DAFTAR') }}
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <a class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500" href="{{ route('login') }}">
                        Sudah punya akun?
                        <span class="font-semibold underline text-pink-600">Login Sekarang.</span>
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fakultasSelect = document.getElementById('fakultas');
        const prodiSelect = document.getElementById('prodi');
        const oldProdi = "{{ old('prodi') }}";

        const prodiData = {
            "Teknik": [
                "Teknik Sipil", "Teknik Mesin", "Teknik Elektro", "Teknologi Informasi",
                "Teknologi Elektro-Medis", "Teknologi Rekayasa Otomotif"
            ],
            "Agama Islam": [
                "Komunikasi dan Penyiaran Islam", "Pendidikan Agama Islam", "Ekonomi Syariah"
            ],
            "Kedokteran & Ilmu Kesehatan": [
                "Kedokteran", "Pendidikan Profesi Dokter", "Pendidikan Profesi Ners",
                "Farmasi", "Apoteker", "Keperawatan"
            ],
            "Kedokteran Gigi": [
                "Kedokteran Gigi", "Profesi Dokter Gigi"
            ],
            "Pertanian": [
                "Agroteknologi", "Agribisnis"
            ],
            "Ilmu Sosial Politik": [
                "Hubungan Internasional", "Ilmu Komunikasi", "Ilmu Pemerintahan",
                "International Program of International Relations (IPIREL)",
                "International Program of Government Affairs and Administration (IGOV)",
                "International Program of Communication Studies (IP-COS)"
            ],
            "Ekonomi & Bisnis": [
                "Manajemen", "Akuntansi", "Ekonomi",
                "International Program of Management and Business (IMaBs)",
                "International Program of Accounting (IPAcc)",
                "International Undergraduate Program for Islamic Economics and Finance (IPIEF)",
                "Magister Manajemen", "Magister Akuntansi", "Magister Ekonomi", "Doktor Manajemen"
            ],
            "Pendidikan Bahasa": [
                "Pendidikan Bahasa Inggris", "Pendidikan Bahasa Arab", "Pendidikan Bahasa Jepang"
            ],
            "Hukum": [
                "Hukum", "Internasional Ilmu Hukum (IPOLS)"
            ],
            "Psikologi": [
                "Psikologi"
            ]
        };

        function updateProdiDropdown(selectedFakultas) {
            prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Program Studi</option>';
            prodiSelect.disabled = true;

            if (selectedFakultas && prodiData[selectedFakultas]) {
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

                prodiSelect.disabled = false;
            }
        }

        fakultasSelect.addEventListener('change', function() {
            updateProdiDropdown(this.value);
        });

        const initialFakultas = fakultasSelect.value;
        if (initialFakultas) {
            updateProdiDropdown(initialFakultas);
        }
    });
</script>