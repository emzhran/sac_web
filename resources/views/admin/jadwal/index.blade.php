@extends('layouts.app')
@section('page_title', 'Admin / Jadwal Lapangan')

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
            Jadwal Pemanfaatan Lapangan
        </h1>
        <p class="text-sm text-gray-500">Jadwal 7 Hari ke depan.</p>
    </div>

    <div class="bg-white shadow-lg shadow-indigo-500/5 rounded-2xl border border-gray-100 p-4 md:p-6">
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 mb-3 block">Filter Lapangan:</label>
            <div class="flex overflow-x-auto pb-2 gap-3 no-scrollbar">
                @php 
                    $filterNames = $allLapangans->map(function($item) {
                        return explode(' ', trim($item->nama))[0];
                    })->unique()->sort()->values();
                    
                    $currentFilterKey = explode(' ', $lapanganFilterName)[0];
                    $activeLap = $allLapangans->firstWhere('nama', $lapanganFilterName);

                    if(!$activeLap) {
                        $activeLap = $allLapangans->filter(function($item) use ($lapanganFilterName) {
                            return str_contains($item->nama, $lapanganFilterName);
                        })->first();
                    }
                    $activeLapId = $activeLap ? $activeLap->id : '';
                @endphp

                @foreach ($filterNames as $lap)
                    <a href="{{ route('admin.jadwal.index', ['lapangan' => $lap]) }}"
                       class="whitespace-nowrap px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border flex-shrink-0
                              {{ $currentFilterKey == $lap 
                                   ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30' 
                                   : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $lap }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="md:hidden flex items-center justify-between text-xs text-gray-400 mb-2 px-1">
            <span>&larr; Geser tabel untuk lihat hari lain &rarr;</span>
        </div>
        <div class="relative rounded-xl border border-gray-200 bg-white overflow-hidden flex flex-col">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="sticky left-0 z-20 w-20 min-w-[5rem] px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase border-r border-b border-gray-200 bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                Waktu
                            </th>
                            @foreach ($dates as $date)
                                <th scope="col" class="min-w-[120px] px-1 py-4 text-center text-xs font-bold uppercase tracking-wider border-b border-gray-200
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
                                <td class="sticky left-0 z-10 px-2 py-3 text-center whitespace-nowrap text-xs font-bold text-gray-900 border-r border-gray-100 bg-white group-hover:bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {{ $jamMulai }}
                                    <div class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $jamSelesai }}</div>
                                </td>

                                @foreach ($dates as $date)
                                    @php
                                        $tanggal = $date->toDateString();
                                        $isBooked = null;

                                        if (isset($allBookings[$lapanganFilterName][$tanggal])) {
                                            foreach ($allBookings[$lapanganFilterName][$tanggal] as $booking) {
                                                if ($jamMulai >= $booking['jam_mulai'] && $jamMulai < $booking['jam_selesai']) {
                                                    $isBooked = $booking;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        $nowWIB = \Carbon\Carbon::now('Asia/Jakarta');

                                        $isDatePast = $date->format('Y-m-d') < $nowWIB->format('Y-m-d');
                                        $isDateToday = $date->format('Y-m-d') === $nowWIB->format('Y-m-d');

                                        $isTimePast = $isDateToday && ($jamMulai < $nowWIB->format('H:i'));
                                        $isPast = $isDatePast || $isTimePast;
                                    @endphp

                                    <td class="px-1 py-1 h-16 align-middle border-l border-dashed border-gray-100 relative">
                                        
                                        @if ($isBooked)
                                            @php
                                                $status = strtolower($isBooked['status']);
                                                $styles = match ($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                                    'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                                    default => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                                };
                                            @endphp
                                            <div class="w-full h-full flex flex-col items-center justify-center rounded-md {{ $styles }} p-1 cursor-default group relative" title="{{ $isBooked['nama'] }} ({{ $status }})">
                                                <span class="text-[10px] font-bold leading-tight truncate w-full px-1 text-center">
                                                    {{ \Illuminate\Support\Str::limit($isBooked['nama'], 12) }}
                                                </span>
                                                <span class="text-[9px] opacity-70 italic mt-0.5">{{ ucfirst($status) }}</span>
                                            </div>

                                        @elseif ($isPast)
                                            <div class="w-full h-full bg-gray-50 rounded-md flex items-center justify-center">
                                                 <span class="text-gray-300 text-[10px]">-</span>
                                            </div>

                                        @else
                                            <a href="{{ route('admin.jadwal.create', [
                                                    'lapangan_id' => $activeLapId,
                                                    'tanggal' => $tanggal,
                                                    'jam_mulai' => $jamMulai
                                                ]) }}"
                                               class="group flex flex-col items-center justify-center w-full h-full rounded-md border border-transparent 
                                                      hover:bg-indigo-50 hover:border-indigo-200 transition-all cursor-pointer">
                                                <span class="text-gray-300 text-lg group-hover:text-indigo-600 group-hover:font-bold leading-none">+</span>
                                                <span class="text-[9px] text-gray-300 group-hover:text-indigo-600 font-medium">Booking</span>
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm leading-relaxed">
                <strong>Catatan Admin:</strong> Klik tombol <span class="text-indigo-600 font-bold">+</span> untuk menambahkan jadwal.
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection