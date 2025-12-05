@extends('layouts.app')

@section('page_title', 'Admin / Tambah Lapangan')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen flex flex-col items-center justify-center">
    
    <div class="w-full max-w-2xl">
        <!-- Header Section -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Tambah Lapangan Baru
            </h1>
            <p class="text-sm text-gray-500">
                Masukkan detail lapangan fisik baru yang akan disewakan.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-xl shadow-indigo-500/10 rounded-2xl border border-gray-100 overflow-hidden">
            
            <!-- Banner -->
            <div class="bg-indigo-600 h-2 w-full"></div>

            <form method="POST" action="{{ route('admin.lapangan.store') }}" class="p-8">
                @csrf

                <div class="space-y-6">
                    <!-- Input Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Nama Lapangan
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="nama" id="nama"
                                class="block w-full rounded-xl border-gray-300 pl-4 py-3 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all"
                                required 
                                value="{{ old('nama') }}" 
                                placeholder="Contoh: Lapangan Futsal A, Badminton Hall B">
                        </div>
                        @error('nama')
                            <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Info Alert -->
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-indigo-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-indigo-800">Tips Penamaan</h3>
                            <div class="mt-1 text-sm text-indigo-700">
                                Gunakan nama yang jelas dan spesifik (misal: "Futsal Indoor", "Basket Outdoor") agar pengguna tidak bingung saat memilih.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.lapangan.index') }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        Simpan Lapangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection