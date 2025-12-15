@extends('layouts.app')
@section('page_title', 'Booking Lapangan')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen">
    
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
            Jadwal & Ketersediaan
        </h1>
        <p class="text-sm text-gray-500">Lihat jadwal 7 hari ke depan.</p>
    </div>

    <div class="bg-white shadow-lg shadow-indigo-500/5 rounded-2xl border border-gray-100 p-4 md:p-6">
        
        {{-- Filter Lapangan (Horizontal Scroll di Mobile) --}}
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 mb-3 block">Pilih Lapangan:</label>
            <div class="flex overflow-x-auto pb-2 gap-3 no-scrollbar">
                @php
                    $allLapangansData = \App\Models\Lapangan::all();
                    $uniqueNames = $allLapangansData->map(fn($lap) => explode(' ', trim($lap->nama))[0])->unique()->sort()->values();
                    $currentFilterBase = explode(' ', $lapanganFilterName)[0];
                    
                    // Logic pencarian ID lapangan global (tetap sama)
                    $foundLapangan = \App\Models\Lapangan::where('nama', $lapanganFilterName)
                        ->orWhere('nama', 'LIKE', $lapanganFilterName . '%') 
                        ->first();
                    if (!$foundLapangan) $foundLapangan = \App\Models\Lapangan::first();
                    $globalLapanganId = $foundLapangan ? $foundLapangan->id : null;
                @endphp

                @foreach ($uniqueNames as $lapName)
                    <a href="{{ route('booking.index', ['lapangan' => $lapName]) }}"
                       class="whitespace-nowrap px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border flex-shrink-0
                              {{ $currentFilterBase == $lapName 
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30' 
                                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $lapName }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Hint untuk Mobile --}}
        <div class="md:hidden flex items-center justify-between text-xs text-gray-400 mb-2 px-1">
            <span>&larr; Geser tabel untuk lihat hari lain &rarr;</span>
        </div>

        {{-- Container Tabel Responsif --}}
        <div class="relative rounded-xl border border-gray-200 bg-white overflow-hidden flex flex-col">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Sticky Header Waktu --}}
                            <th scope="col" class="sticky left-0 z-20 w-20 min-w-[5rem] px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase border-r border-b border-gray-200 bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                Waktu
                            </th>
                            
                            {{-- Header Hari (Lebar minimum agar nyaman di HP) --}}
                            @foreach ($dates as $date)
                                <th scope="col" class="min-w-[100px] px-1 py-4 text-center text-xs font-bold uppercase tracking-wider border-b border-gray-200
                                    {{ $date->isToday() ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">
                                    <div class="flex flex-col">
                                        <span>{{ strtoupper($date->translatedFormat('D')) }}</span>
                                        <span class="text-[10px] font-normal opacity-80 mt-1">{{ $date->translatedFormat('d M') }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($timeSlots as $jamMulai)
                            @php
                                $jamSelesai = sprintf('%02d:00', (int)substr($jamMulai, 0, 2) + 1);
                            @endphp
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                
                                {{-- Sticky Column Waktu --}}
                                <td class="sticky left-0 z-10 px-2 py-3 text-center whitespace-nowrap text-xs font-bold text-gray-900 border-r border-gray-100 bg-white group-hover:bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {{ $jamMulai }}
                                    <div class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $jamSelesai }}</div>
                                </td>

                                @foreach ($dates as $date)
                                    @php
                                        $tanggal = $date->toDateString();
                                        $isBooked = null;

                                        // Logic Booking (Sama seperti sebelumnya)
                                        if (isset($allBookings[$lapanganFilterName][$tanggal])) {
                                            foreach ($allBookings[$lapanganFilterName][$tanggal] as $booking) {
                                                if ($jamMulai >= $booking['jam_mulai'] && $jamMulai < $booking['jam_selesai']) {
                                                    $isBooked = $booking;
                                                    break;
                                                }
                                            }
                                        }
                                        $isPast = $date->isBefore(now()->startOfDay()) || ($date->isToday() && $jamMulai < now()->format('H:i'));
                                    @endphp

                                    <td class="px-1 py-1 h-16 align-middle border-l border-dashed border-gray-100 relative">
                                        
                                        @if ($isBooked)
                                            @php
                                                $status = strtolower($isBooked['status']);
                                                $colors = match ($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    default => 'bg-rose-100 text-rose-700 border-rose-200',
                                                };
                                                $icon = match ($status) {
                                                    'approved' => '✓',
                                                    'pending' => '⏳',
                                                    default => '✕',
                                                };
                                            @endphp
                                            <div class="w-full h-full flex flex-col items-center justify-center rounded-lg border {{ $colors }} p-1 cursor-not-allowed select-none">
                                                <span class="text-xs font-bold">{{ $icon }}</span>
                                                <span class="text-[9px] font-bold leading-tight truncate w-full text-center mt-0.5">
                                                    {{ \Illuminate\Support\Str::limit($isBooked['nama'], 8, '..') }}
                                                </span>
                                            </div>

                                        @elseif ($isPast)
                                            <div class="w-full h-full bg-gray-50 rounded-lg flex items-center justify-center border border-transparent">
                                                 <span class="text-gray-300 text-xs font-bold">-</span>
                                            </div>

                                        @else
                                            @if($globalLapanganId)
                                                <a href="{{ route('booking.create', [
                                                    'lapangan' => $globalLapanganId, 
                                                    'tanggal' => $tanggal, 
                                                    'jam_mulai' => $jamMulai,
                                                    'jam_selesai' => $jamSelesai
                                                ]) }}"
                                                class="group flex flex-col items-center justify-center w-full h-full rounded-lg border border-indigo-100 bg-white 
                                                       hover:bg-indigo-600 hover:border-indigo-600 transition-all cursor-pointer shadow-sm">
                                                    <span class="text-indigo-400 font-bold text-lg group-hover:text-white leading-none">+</span>
                                                    <span class="text-[9px] text-indigo-300 group-hover:text-indigo-100 font-medium">Book</span>
                                                </a>
                                            @else
                                                <span class="text-rose-400 text-[10px] block text-center">Error</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Legend / Keterangan Warna --}}
        <div class="mt-6 flex flex-wrap gap-4 text-xs text-gray-600 justify-center md:justify-start">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-white border border-indigo-100"></span>
                <span>Kosong</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-amber-100 border border-amber-200"></span>
                <span>Menunggu</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-emerald-100 border border-emerald-200"></span>
                <span>Dibooking</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-gray-50"></span>
                <span>Lewat</span>
            </div>
        </div>

    </div>
</div>

<style>
    /* Utility untuk menyembunyikan scrollbar tapi tetap bisa scroll */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection