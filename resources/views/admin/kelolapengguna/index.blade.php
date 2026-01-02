@extends('layouts.app')

@section('page_title', 'Kelola Pengguna')

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen w-full max-w-full overflow-x-hidden">
    
    <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
                Manajemen Pengguna
            </h1>
            <p class="text-xs md:text-sm text-gray-500">
                Kelola data mahasiswa, cari, dan filter berdasarkan data akademik.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <form action="{{ url()->current() }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block">Cari Nama / Email</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all outline-none" 
                            placeholder="Kata kunci...">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block">NIM</label>
                    <input type="text" name="nim" value="{{ request('nim') }}" 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all outline-none" 
                        placeholder="NIM...">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block">Fakultas</label>
                    <select name="fakultas" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all outline-none cursor-pointer bg-white">
                        <option value="">Semua Fakultas</option>
                        @foreach($fakultasData as $fakultas => $prodis)
                            <option value="{{ $fakultas }}" {{ request('fakultas') == $fakultas ? 'selected' : '' }}>
                                {{ $fakultas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block">Program Studi</label>
                    <select name="prodi" 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all outline-none cursor-pointer bg-white">
                        <option value="">Semua Prodi</option>
                        @php
                            $selectedFakultas = request('fakultas');
                            $listProdi = $selectedFakultas 
                                ? ($fakultasData[$selectedFakultas] ?? []) 
                                : collect($fakultasData)->flatten()->sort()->values();
                        @endphp
                        @foreach($listProdi as $prodi)
                             <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                                {{ $prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end items-center gap-3 mt-4 pt-4 border-t border-gray-50">
                @if(request()->hasAny(['search', 'nim', 'fakultas', 'prodi']))
                    <a href="{{ url()->current() }}" class="text-sm text-gray-500 hover:text-rose-600 font-medium transition-colors">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-transparent md:bg-white md:shadow-xl md:shadow-indigo-500/5 rounded-2xl md:border border-gray-100 overflow-hidden">
        
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 text-sm font-medium border border-emerald-100 flex items-center gap-2 rounded-xl mb-4 md:mb-0 md:rounded-none md:border-x-0 md:border-t-0">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-rose-50 text-rose-700 p-4 text-sm font-medium border border-rose-100 flex items-center gap-2 rounded-xl mb-4 md:mb-0 md:rounded-none md:border-x-0 md:border-t-0">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        @if($users->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center bg-white rounded-2xl border border-gray-100 md:border-none shadow-sm md:shadow-none">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm md:text-base mb-4">
                    Tidak ada data pengguna yang cocok dengan kriteria pencarian Anda.
                </p>
                <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                    Reset Filter
                </a>
            </div>
        @else
            
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Mahasiswa</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fakultas & Prodi</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($users as $index => $user)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">
                                    {{ $index + 1 }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm flex-shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">{{ $user->name }}</span>
                                            @if($user->nim)
                                                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200">{{ $user->nim }}</span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900 mb-0.5">{{ $user->prodi ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->fakultas ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center border border-amber-100 hover:border-amber-500" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" 
                                                onclick="confirmDelete('delete-form-{{ $user->id }}', '{{ $user->name }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center border border-rose-100 hover:border-rose-600" 
                                                title="Hapus Pengguna">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden space-y-4 pb-4">
                @foreach ($users as $user)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3 w-full">
                                    <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100 flex-shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden w-full">
                                        <h3 class="font-bold text-gray-900 text-base truncate">{{ $user->name }}</h3>
                                        @if($user->nim)
                                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200 font-mono">{{ $user->nim }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic block mt-1">Tanpa NIM</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 mb-3 border border-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 block mb-0.5 font-bold">Program Studi</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ $user->prodi ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 block mb-0.5 font-bold">Fakultas</span>
                                        <span class="text-sm text-gray-600">{{ $user->fakultas ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-500 pt-2 border-t border-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="truncate">{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 border-t border-gray-100 bg-gray-50/50">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center justify-center gap-2 py-3 text-sm font-semibold text-amber-600 hover:bg-amber-50 transition-colors border-r border-gray-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>

                            <form id="mobile-delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="block w-full">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                    onclick="confirmDelete('mobile-delete-form-{{ $user->id }}', '{{ $user->name }}')"
                                    class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(formId, userName) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                html: `Anda akan menghapus user <b>${userName}</b>.<br><span class="text-sm text-gray-500">Data ini tidak dapat dikembalikan.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-rose-500/30',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
    </script>
@endpush