@extends('layouts.app')

@section('page_title', 'Kelola Riwayat Peminjaman')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Semua Riwayat Booking
            </h1>
            <p class="text-sm text-gray-500">
                Daftar lengkap riwayat peminjaman lapangan dari semua mahasiswa.
            </p>
        </div>

        <div>
            <button onclick="openExportModal()" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    <div class="bg-white shadow-xl shadow-indigo-500/5 rounded-2xl border border-gray-100 overflow-hidden">
        
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 text-sm font-medium border-b border-emerald-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($riwayats->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada data riwayat peminjaman.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User Pemesan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lapangan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Jadwal</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($riwayats as $index => $r)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $riwayats->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $r->user->name ?? 'User Tidak Dikenal' }}</span>
                                        <span class="text-xs text-gray-500">{{ $r->user->email ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                                            @if(Str::contains($r->lapangan->nama ?? '', 'Futsal')) ⚽ 
                                            @elseif(Str::contains($r->lapangan->nama ?? '', 'Basket')) 🏀 
                                            @elseif(Str::contains($r->lapangan->nama ?? '', 'Voli')) 🏐 
                                            @else 🏸 @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $r->lapangan->nama ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($r->jadwals && $r->jadwals->isNotEmpty())
                                        @foreach($r->jadwals as $jadwal)
                                            <div class="mb-1 last:mb-0">
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500 ml-2 bg-gray-100 px-2 py-0.5 rounded">
                                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-red-400 italic">Data jadwal tidak ditemukan</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusStyles = match($r->status) {
                                            'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                            'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                            'rejected' => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                        
                                        $statusLabel = match($r->status) {
                                            'approved' => 'Disetujui',
                                            'pending' => 'Pending',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($r->status)
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>
</div>

<div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeExportModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.riwayat.export') }}" method="GET">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Export Data ke Excel
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Silahkan pilih rentang tanggal data yang ingin di-export.
                                </p>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                        <input type="date" name="start_date" id="start_date" required 
                                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                        <input type="date" name="end_date" id="end_date" required 
                                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Download Excel
                    </button>
                    <button type="button" onclick="closeExportModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
    }
</script>
@endsection