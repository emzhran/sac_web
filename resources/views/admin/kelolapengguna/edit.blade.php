@extends('layouts.app')

@section('page_title', 'Edit Pengguna')

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen">
    
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Manajemen Pengguna
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Edit Data Mahasiswa
            </h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-bold text-gray-700 mb-2">NIM</label>
                                <input type="text" id="nim" name="nim" value="{{ old('nim', $user->nim) }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-mono @error('nim') border-red-500 @enderror"
                                    placeholder="Nomor Induk Mahasiswa">
                                @error('nim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-100 mt-2">
                            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Data Akademik
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="fakultas" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Fakultas</label>
                                    <select id="fakultas" name="fakultas" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none cursor-pointer bg-white">
                                        <option value="">-- Pilih Fakultas --</option>
                                        @foreach($fakultasData as $fakultas => $prodis)
                                            <option value="{{ $fakultas }}" {{ old('fakultas', $user->fakultas) == $fakultas ? 'selected' : '' }}>
                                                {{ $fakultas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="prodi" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Program Studi</label>
                                    <select id="prodi" name="prodi"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none cursor-pointer bg-white">
                                        <option value="">-- Pilih Fakultas Dulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fakultasData = @json($fakultasData);
        
        const fakultasSelect = document.getElementById('fakultas');
        const prodiSelect = document.getElementById('prodi');
        const oldProdi = "{{ old('prodi', $user->prodi) }}";

        function updateProdiOptions() {
            const selectedFakultas = fakultasSelect.value;
            prodiSelect.innerHTML = '<option value="">-- Pilih Program Studi --</option>';
            if (selectedFakultas && fakultasData[selectedFakultas]) {
                const prodis = fakultasData[selectedFakultas];
                
                prodis.forEach(function(prodi) {
                    const option = document.createElement('option');
                    option.value = prodi;
                    option.textContent = prodi;
                    if (prodi === oldProdi) {
                        option.selected = true;
                    }
                    
                    prodiSelect.appendChild(option);
                });
            }
        }
        fakultasSelect.addEventListener('change', updateProdiOptions);
        updateProdiOptions();
    });
</script>
@endsection