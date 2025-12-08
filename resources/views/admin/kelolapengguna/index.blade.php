@extends('layouts.app')

@section('page_title', 'Kelola Pengguna')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Manajemen Pengguna
            </h1>
            <p class="text-sm text-gray-500">
                Kelola data mahasiswa yang terdaftar dalam sistem.
            </p>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white shadow-xl shadow-indigo-500/5 rounded-2xl border border-gray-100 overflow-hidden">
        
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 text-sm font-medium border-b border-emerald-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($users->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pengguna</h3>
                <p class="text-gray-500">Belum ada data pengguna yang terdaftar di sistem.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NIM</th>
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
                                        <!-- Avatar Initials -->
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-400">Terdaftar: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->nim)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200 font-mono">
                                            {{ $user->nim }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada NIM</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Button (Placeholder href) -->
                                        <a href="#" 
                                           class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center border border-amber-100 hover:border-amber-500" 
                                           title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button type="button" 
                                            onclick="confirmDelete(null, '{{ $user->name }}')"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center border border-rose-100 hover:border-rose-600" 
                                            title="Hapus Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(form, userName) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                html: `Anda akan menghapus user <b>${userName}</b>.<br><span class="text-sm text-gray-500">Data booking dan riwayat user ini mungkin akan hilang.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', // Rose-500
                cancelButtonColor: '#9ca3af',  // Gray-400
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else {
                        // Demo only if no form provided
                        Swal.fire('Info', 'Fitur hapus belum terhubung ke route.', 'info');
                    }
                }
            });
        }
    </script>
@endpush