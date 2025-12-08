@extends('layouts.app')
@section('page_title', 'Riwayat Peminjaman')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Riwayat Peminjaman
            </h1>
            <p class="text-sm text-gray-500">
                Daftar semua pengajuan peminjaman lapangan Anda.
            </p>
        </div>
    </div>

    <div class="bg-white shadow-xl shadow-indigo-500/5 rounded-2xl border border-gray-100 overflow-hidden">

        @if ($riwayats->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-gray-500 max-w-sm mb-8">
                Anda belum pernah melakukan peminjaman lapangan. Yuk, mulai olahraga sekarang!
            </p>
            <a href="{{ route('booking.index') }}" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30">
                Cari Lapangan
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lapangan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal Main</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($riwayats as $r)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-xl">
                                    @if(Str::contains($r->lapangan->nama ?? '', 'Futsal')) ⚽
                                    @elseif(Str::contains($r->lapangan->nama ?? '', 'Basket')) 🏀
                                    @elseif(Str::contains($r->lapangan->nama ?? '', 'Voli')) 🏐
                                    @else 🏸 @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $r->lapangan->nama ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">Olahraga</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($r->jadwals && $r->jadwals->isNotEmpty())
                                @foreach($r->jadwals as $jadwal)
                                <div class="mb-2 last:mb-0">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    </div>
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
                                'pending' => 'Menunggu',
                                'rejected' => 'Ditolak',
                                default => ucfirst($r->status)
                            };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles }}">
                                @if($r->status == 'approved')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($r->status == 'pending')
                                    <svg class="w-3 h-3 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($r->status == 'rejected')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($r->status == 'approved')
                                <a href="{{ route('riwayat.pdf', $r->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
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
@endsection