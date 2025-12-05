@extends('layouts.app')

@section('page_title', 'Dashboard User')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Halo, {{ explode(' ', $user->name)[0] }}! 👋
            </h1>
            <p class="text-sm text-gray-500">Selamat datang di Sport Activity Center</p>
        </div>

        <a href="#" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-indigo-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Booking Baru
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30 p-6 rounded-2xl relative overflow-hidden group hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">Jadwal Main</span>
                </div>
                
                @if($nextSchedule)
                    <p class="text-sm font-medium opacity-90 mb-1">{{ $nextSchedule['field'] }}</p>
                    <p class="text-xl font-bold">{{ $nextSchedule['date'] }}</p>
                    <p class="text-sm opacity-80 mt-1">{{ $nextSchedule['time'] }} WIB</p>
                @else
                    <p class="text-sm font-medium opacity-90 mb-1">Tidak ada jadwal</p>
                    <p class="text-lg font-bold">Booking Sekarang!</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-lg p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @if($pendingBookings > 0)
                    <span class="flex items-center gap-1 text-xs font-semibold bg-amber-100 text-amber-700 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Menunggu ACC
                    </span>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-500 mb-2">Status Pending</p>
                <p class="text-4xl font-bold text-gray-900">{{ $pendingBookings }}</p>
            </div>
        </div>

        <div class="bg-white shadow-lg p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-2">Total Riwayat Booking</p>
                <p class="text-4xl font-bold text-gray-900">{{ $totalBookings }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Pengajuan</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lapangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($myBookings->take(5) as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                            @if(Str::contains($booking->lapangan->nama, 'Futsal')) ⚽ 
                                            @elseif(Str::contains($booking->lapangan->nama, 'Basket')) 🏀 
                                            @elseif(Str::contains($booking->lapangan->nama, 'Voli')) 🏐 
                                            @else 🏸 @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $booking->lapangan->nama }}</p>
                                            <p class="text-xs text-gray-500">ID: #{{ $booking->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @foreach($booking->jadwal as $jadwal)
                                            <span class="text-sm text-gray-700 font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($booking->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                    @elseif($booking->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada riwayat booking.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 px-1">Fasilitas Tersedia</h3>
            
            @foreach($fields as $field)
            <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-100 hover:shadow-lg transition-all duration-200 group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl 
                            {{ $loop->iteration % 2 == 0 ? 'bg-blue-50 text-blue-500' : 'bg-orange-50 text-orange-500' }} 
                            flex items-center justify-center text-xl">
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
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="bg-indigo-900 rounded-2xl p-6 text-white mt-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10"></div>
                <h4 class="font-bold text-lg mb-2 relative z-10">Butuh Bantuan?</h4>
                <p class="text-indigo-200 text-sm mb-4 relative z-10">Hubungi admin jika ada kendala peminjaman.</p>
                <button class="w-full bg-white text-indigo-900 font-semibold py-2 rounded-lg hover:bg-indigo-50 transition-colors relative z-10 text-sm">
                    Hubungi via WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>
@endsection