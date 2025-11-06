<section class="p-6 bg-white rounded-xl shadow-lg border border-gray-200">
    <header>
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __("Informasi akun Anda bersifat tetap dan tidak dapat diubah setelah registrasi.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nim" :value="__('NIM')" />
            <x-text-input id="nim" name="nim" type="text" 
                class="mt-1 block w-full bg-gray-100 border-gray-300 focus:ring-pink-500" 
                :value="old('nim', $user->nim)" disabled />
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" 
                class="mt-1 block w-full bg-gray-100 border-gray-300 focus:ring-pink-500" 
                :value="old('name', $user->name)" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        
        <div>
            <x-input-label for="fakultas" :value="__('Fakultas')" />
            <x-text-input id="fakultas" name="fakultas" type="text" 
                class="mt-1 block w-full bg-gray-100 border-gray-300 focus:ring-pink-500" 
                :value="old('fakultas', $user->fakultas)" disabled />
        </div>

        <div>
            <x-input-label for="prodi" :value="__('Program Studi')" />
            <x-text-input id="prodi" name="prodi" type="text" 
                class="mt-1 block w-full bg-gray-100 border-gray-300 focus:ring-pink-500" 
                :value="old('prodi', $user->prodi)" disabled />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" 
                class="mt-1 block w-full bg-gray-100 border-gray-300 focus:ring-pink-500" 
                :value="old('email', $user->email)" disabled />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-sm text-pink-600 hover:text-pink-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <p class="text-sm text-gray-600 italic">
                Silhakan hubungi admin jika ada kesalahan data.
            </p>
        </div>
    </form>
</section>