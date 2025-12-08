<section>
    <header class="flex items-start justify-between mb-8">
        <div>
            <h2 class="text-xl font-bold text-gray-900">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ __("Informasi akun Anda bersifat tetap.") }}
            </p>
        </div>
        <div class="hidden sm:flex h-12 w-12 rounded-full bg-indigo-100 items-center justify-center text-indigo-600 font-bold text-xl">
            {{ substr($user->name, 0, 1) }}
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1">
                <x-input-label for="nim" :value="__('NIM')" class="text-gray-600 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .883.393 1.627 1 2.188m.422 5.176A1.001 1.001 0 009 13.001h6a1 1 0 10-1.422-1.636l-.348-.114-1.23-.393"/></svg>
                    </div>
                    <x-text-input id="nim" name="nim" type="text" 
                        class="pl-10 block w-full bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed rounded-xl" 
                        :value="old('nim', $user->nim)" disabled />
                </div>
            </div>

            <div class="col-span-1">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-600 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <x-text-input id="name" name="name" type="text" 
                        class="pl-10 block w-full bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed rounded-xl" 
                        :value="old('name', $user->name)" disabled />
                </div>
            </div>

            <div class="col-span-1">
                <x-input-label for="fakultas" :value="__('Fakultas')" class="text-gray-600 font-medium" />
                <x-text-input id="fakultas" name="fakultas" type="text" 
                    class="mt-1 block w-full bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed rounded-xl" 
                    :value="old('fakultas', $user->fakultas)" disabled />
            </div>

            <div class="col-span-1">
                <x-input-label for="prodi" :value="__('Program Studi')" class="text-gray-600 font-medium" />
                <x-text-input id="prodi" name="prodi" type="text" 
                    class="mt-1 block w-full bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed rounded-xl" 
                    :value="old('prodi', $user->prodi)" disabled />
            </div>

            <div class="col-span-1 md:col-span-2">
                <x-input-label for="email" :value="__('Email')" class="text-gray-600 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <x-text-input id="email" name="email" type="email" 
                        class="pl-10 block w-full bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed rounded-xl" 
                        :value="old('email', $user->email)" disabled />
                </div>
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-4">
                <p class="text-sm text-amber-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ __('Alamat email Anda belum diverifikasi.') }}
                </p>
                <button form="send-verification" class="mt-2 text-sm font-semibold text-amber-900 underline hover:text-amber-700">
                    {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                </button>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 mt-6">
            <p class="text-xs text-gray-400 italic flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Silakan hubungi admin jika terdapat kesalahan data profil.
            </p>
        </div>
    </form>
</section>