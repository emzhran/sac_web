@extends('layouts.app')
@section('page_title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto mb-8">
        <a href="{{ route('riwayat.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-4 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Riwayat
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Detail Peminjaman
            </h1>
            @php
                $statusStyles = match($booking->status) {
                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                    'default' => 'bg-gray-100 text-gray-700 border-gray-200'
                };
                $statusLabel = match($booking->status) {
                    'approved' => 'Disetujui',
                    'pending' => 'Menunggu Konfirmasi',
                    'rejected' => 'Ditolak',
                    'default' => ucfirst($booking->status)
                };
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-bold border {{ $statusStyles }} flex items-center justify-center md:justify-start w-full md:w-fit">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 md:p-6 border-b border-gray-100 flex items-start gap-4">
                    <div class="h-12 w-12 md:h-16 md:w-16 rounded-xl bg-indigo-50 flex items-center justify-center text-2xl md:text-3xl shrink-0">
                        @if(Str::contains($booking->lapangan->nama ?? '', 'Futsal')) ⚽
                        @elseif(Str::contains($booking->lapangan->nama ?? '', 'Basket')) 🏀
                        @elseif(Str::contains($booking->lapangan->nama ?? '', 'Voli')) 🏐
                        @else 🏸 @endif
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-900">{{ $booking->lapangan->nama ?? 'Nama Lapangan Tidak Tersedia' }}</h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $booking->lapangan->lokasi ?? 'Lokasi Kampus Terpadu UMY' }}</p>
                    </div>
                </div>
                
                <div class="p-5 md:p-6 bg-gray-50/50">
                    <h3 class="text-xs md:text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Jadwal Main</h3>
                    <div class="grid gap-3">
                        @foreach($booking->jadwals as $jadwal)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm gap-2 sm:gap-0">
                            <div class="flex items-center gap-3">
                                <div class="bg-indigo-100 p-2 rounded-md text-indigo-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-medium text-gray-700 text-sm md:text-base">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="font-bold text-gray-900 bg-gray-100 px-3 py-1.5 rounded-md text-sm text-center sm:text-right">
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(!empty($booking->catatan))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
                <h3 class="text-xs md:text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Catatan Peminjaman</h3>
                <p class="text-gray-600 bg-amber-50 p-4 rounded-xl border border-amber-100 text-sm italic">
                    "{{ $booking->catatan }}"
                </p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            
            @if($booking->status == 'approved')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('riwayat.pdf', $booking->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Bukti PDF
                    </a>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Informasi</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between items-center">
                        <span class="text-gray-500">Tanggal Pengajuan</span>
                        <span class="font-medium text-gray-900">{{ $booking->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-500">Peminjam</span>
                        <span class="font-medium text-gray-900 text-right">{{ $booking->user->name }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium text-gray-900 break-all text-right ml-4">{{ $booking->user->email }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection