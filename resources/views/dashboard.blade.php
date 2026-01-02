@extends('layouts.app')

@section('page_title', 'Dashboard User')

@section('header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
                    Halo, <span class="text-indigo-600">{{ explode(' ', $user->name)[0] }}</span>! 👋
                </h1>
                <p class="text-sm text-gray-500">Selamat datang kembali di Sport Activity Center.</p>
            </div>

            <a href="{{ route('booking.index') }}" class="w-full md:w-auto flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Booking Baru
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 p-6 rounded-2xl relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/10">Jadwal Terdekat</span>
                    </div>
                    
                    @if($nextSchedule)
                        <div class="space-y-1">
                            <p class="text-sm font-medium opacity-90">{{ $nextSchedule['field'] }}</p>
                            <p class="text-2xl font-bold tracking-tight">{{ $nextSchedule['date'] }}</p>
                            <p class="text-sm opacity-80 bg-emerald-700/30 inline-block px-2 py-0.5 rounded mt-1">
                                ⏰ {{ $nextSchedule['time'] }} WIB
                            </p>
                        </div>
                    @else
                        <div class="space-y-1">
                            <p class="text-sm font-medium opacity-90">Belum ada jadwal</p>
                            <p class="text-lg font-bold">Siap berolahraga?</p>
                            <a href="{{ route('booking.index') }}" class="text-xs opacity-90 mt-2 hover:underline hover:text-white flex items-center gap-1">
                                Cek ketersediaan lapangan &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white shadow-md p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-full -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        @if($pendingBookings > 0)
                        <span class="flex items-center gap-1.5 text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1 rounded-full">
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                            Menunggu ACC
                        </span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Status Pending</p>
                    <p class="text-3xl md:text-4xl font-bold text-gray-900">{{ $pendingBookings }} <span class="text-base font-normal text-gray-400">Booking</span></p>
                </div>
            </div>
            <div class="bg-white shadow-md p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-transparent rounded-full -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Riwayat</p>
                    <p class="text-3xl md:text-4xl font-bold text-gray-900">{{ $totalBookings }} <span class="text-base font-normal text-gray-400">Kali Main</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow-lg shadow-gray-100 rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Riwayat Pengajuan</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Wajib konfirmasi 1 jam sebelum main
                            </p>
                        </div>
                        <a href="{{ route('riwayat.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fasilitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($myBookings->take(5) as $booking)
                                    
                                    @php
                                        $jadwalPertama = $booking->jadwals->first();
                                        $waktuMulai = \Carbon\Carbon::parse(
                                            $jadwalPertama->tanggal . ' ' . $jadwalPertama->jam_mulai, 
                                            'Asia/Jakarta'
                                        );
                                        $sekarang = \Carbon\Carbon::now('Asia/Jakarta');
                                        $selisihJam = $sekarang->diffInHours($waktuMulai, false);
                                        $isUpcoming = $booking->status == 'approved' && $waktuMulai->isFuture();
                                        $isUrgent = $isUpcoming && $selisihJam <= 24;
                                        $showVerifyButton = $isUrgent; 
                                    @endphp

                                <tr class="transition-colors {{ $isUrgent ? 'bg-amber-50 hover:bg-amber-100' : 'hover:bg-gray-50/80' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg {{ $isUrgent ? 'bg-amber-100 text-amber-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center text-lg flex-shrink-0">
                                                @if(Str::contains($booking->lapangan->nama, 'Futsal')) ⚽ 
                                                @elseif(Str::contains($booking->lapangan->nama, 'Basket')) 🏀 
                                                @elseif(Str::contains($booking->lapangan->nama, 'Voli')) 🏐 
                                                @else 🏸 @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $booking->lapangan->nama }}</p>
                                                <p class="text-xs text-gray-500">ID: #{{ $booking->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            @foreach($booking->jadwals as $jadwal)
                                                <span class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</span>
                                                <span class="text-xs text-gray-500 bg-white/50 border border-gray-200 px-2 py-0.5 rounded w-fit mt-1">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }} WIB</span>
                                                
                                                @if($isUrgent)
                                                    <span class="text-[10px] text-amber-600 font-bold mt-1 animate-pulse">
                                                        {{ $selisihJam < 1 ? 'Segera!' : 'Main dalam ' . round($selisihJam) . ' jam' }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            @if($booking->status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                    Menunggu
                                                </span>
                                            @elseif($booking->status == 'approved')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    Disetujui
                                                </span>
                                                @if($showVerifyButton)
                                                    <a href="{{ route('riwayat.index') }}" class="group relative w-full inline-flex justify-center items-center gap-1 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-indigo-700 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                                        <span class="absolute right-0 top-0 -mt-1 -mr-1 flex h-3 w-3">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                        </span>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Konfirmasi Hadir
                                                    </a>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">Belum ada riwayat booking.</p>
                                            <p class="text-gray-400 text-sm">Mulai olahraga dengan booking lapangan sekarang.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-transparent">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="text-lg font-bold text-gray-900">Fasilitas Tersedia</h3>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($fields as $field)
                        <a href="{{ route('booking.index', ['lapangan' => $field->nama]) }}" class="block bg-white shadow-sm rounded-xl p-4 border border-gray-100 hover:shadow-md hover:border-indigo-100 hover:-translate-y-1 transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl 
                                        {{ $loop->iteration % 2 == 0 ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }} 
                                        flex items-center justify-center text-xl shadow-inner">
                                        @if(Str::contains($field->nama, 'Futsal')) ⚽ 
                                        @elseif(Str::contains($field->nama, 'Basket')) 🏀 
                                        @elseif(Str::contains($field->nama, 'Voli')) 🏐 
                                        @else 🏸 @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $field->nama }}</h4>
                                        <p class="text-xs text-gray-500">Lihat Jadwal & Booking</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('email_unverified'))
    @include('components.email-verification-modal') 
@endif

@endsection