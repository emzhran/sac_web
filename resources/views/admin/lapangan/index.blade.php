@extends('layouts.app')

@section('page_title', 'Admin / Manajemen Lapangan')

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen w-full max-w-full overflow-x-hidden">
    
    <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
                Manajemen Lapangan
            </h1>
            <p class="text-xs md:text-sm text-gray-500">
                Kelola daftar fasilitas lapangan yang tersedia untuk dipinjam.
            </p>
        </div>

        <a href="{{ route('admin.lapangan.create') }}" 
           class="inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 w-full md:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Lapangan
        </a>
    </div>

    <div class="bg-white md:shadow-xl md:shadow-indigo-500/5 rounded-2xl border border-gray-100 overflow-hidden bg-transparent md:bg-white border-none md:border-solid">
        
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 text-sm font-medium border-b border-emerald-100 flex items-center gap-2 rounded-xl mb-4 md:mb-0 md:rounded-none">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($lapangans->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center bg-white rounded-2xl border border-gray-100 md:border-none">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Lapangan</h3>
                <p class="text-gray-500 mb-6 max-w-xs mx-auto text-sm md:text-base">Data lapangan fisik belum ditambahkan ke dalam sistem.</p>
                <a href="{{ route('admin.lapangan.create') }}" class="text-indigo-600 font-medium hover:text-indigo-700 hover:underline">
                    + Tambah Data Pertama
                </a>
            </div>
        @else
            
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lapangan</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($lapangans as $lapangan)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                                            @if(Str::contains($lapangan->nama, 'Futsal')) ⚽ 
                                            @elseif(Str::contains($lapangan->nama, 'Basket')) 🏀 
                                            @elseif(Str::contains($lapangan->nama, 'Voli')) 🏐 
                                            @else 🏸 @endif
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">{{ $lapangan->nama }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.lapangan.edit', $lapangan) }}" 
                                           class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center border border-amber-100 hover:border-amber-500" 
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                onclick="confirmDelete(this.form, '{{ $lapangan->nama }}')"
                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center border border-rose-100 hover:border-rose-600" 
                                                title="Hapus">
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

            <div class="md:hidden space-y-4">
                @foreach ($lapangans as $lapangan)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
                                @if(Str::contains($lapangan->nama, 'Futsal')) ⚽ 
                                @elseif(Str::contains($lapangan->nama, 'Basket')) 🏀 
                                @elseif(Str::contains($lapangan->nama, 'Voli')) 🏐 
                                @else 🏸 @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-lg truncate">{{ $lapangan->nama }}</h3>
                                <p class="text-xs text-gray-400">ID: {{ $lapangan->id }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('admin.lapangan.edit', $lapangan) }}" 
                               class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-amber-50 text-amber-700 font-semibold text-sm border border-amber-100 hover:bg-amber-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                    onclick="confirmDelete(this.form, '{{ $lapangan->nama }}')"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-rose-50 text-rose-700 font-semibold text-sm border border-rose-100 hover:bg-rose-100 transition-colors">
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
        function confirmDelete(form, lapanganNama) {
            Swal.fire({
                title: 'Hapus Lapangan?',
                html: `Anda akan menghapus <b>${lapanganNama}</b>.<br><span class="text-sm text-gray-500">Semua riwayat booking terkait lapangan ini juga akan terhapus permanen.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', // Rose-500
                cancelButtonColor: '#9ca3af',  // Gray-400
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush