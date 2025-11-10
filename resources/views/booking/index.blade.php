@extends('layouts.app')
@section('page_title', 'Pages / Booking Lapangan')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Booking Lapangan & Jadwal Ketersediaan
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">Jadwal Lapangan 7 Hari ke Depan</h3>

    <div class="flex space-x-3 mb-6">
        @php
            $allLapangans = \App\Models\Lapangan::select('nama')
                ->get()
                ->map(fn($lap) => explode(' ', trim($lap->nama))[0]) 
                ->unique()
                ->values();

            $currentFilterBase = explode(' ', $lapanganFilterName)[0];
        @endphp

        @foreach ($allLapangans as $lap)
            <a href="{{ route('booking.index', ['lapangan' => $lap]) }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold border transition
                      {{ $currentFilterBase == $lap ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                {{ $lap }}
            </a>
        @endforeach
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                        Waktu
                    </th>
                    @foreach ($dates as $date)
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider
                                   {{ $date->isToday() ? 'bg-sky-100 font-bold' : '' }}">
                            {{ $date->translatedFormat('D, d/M') }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($timeSlots as $jamMulai)
                    @php
                        $jamSelesai = sprintf('%02d:00', (int)substr($jamMulai, 0, 2) + 1);
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r bg-gray-50">
                            {{ $jamMulai }} - {{ $jamSelesai }}
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
                                $lapanganId = optional(\App\Models\Lapangan::where('nama', $lapanganFilterName)->first())->id;
                                $isPast = $date->isBefore(now()->startOfDay());
                            @endphp

                            <td class="px-3 py-2 text-sm text-center">
                                @if ($isBooked)
                                    @php
                                        $status = strtolower($isBooked['status']);
                                        $colorClass = match ($status) {
                                            'approved' => 'bg-green-100 border-green-300 text-green-700',
                                            'pending' => 'bg-gray-100 border-gray-300 text-gray-700',
                                            default => 'bg-red-100 border-red-300 text-red-700',
                                        };
                                    @endphp
                                    <div class="p-1 rounded border text-center text-xs font-semibold {{ $colorClass }}">
                                        {{ $isBooked['nama'] }}
                                        <div class="text-xs italic">({{ ucfirst($status) }})</div>
                                    </div>
                                @elseif ($isPast)
                                    <span class="text-gray-400 text-xs italic">Waktu Lampau</span>
                                @else
                                    <a href="{{ route('booking.create', [
                                            'lapangan' => $lapanganId, 
                                            'tanggal' => $tanggal, 
                                            'jam_mulai' => $jamMulai,
                                            'jam_selesai' => $jamSelesai
                                        ]) }}"
                                        class="block h-full w-full py-1 text-gray-400 text-xs italic transition-all duration-150 rounded 
                                        hover:bg-green-100 hover:text-green-700 hover:font-semibold cursor-pointer">
                                        Kosong
                                    </a>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
        <strong>Catatan:</strong> Klik pada jadwal waktu yang bertuliskan <strong>"Kosong".</strong>
        Jadwal ditampilkan hingga <strong>7 hari ke depan.</strong> Harap periksa jadwal sebelum melakukan booking.
    </div>
</div>
@endsection