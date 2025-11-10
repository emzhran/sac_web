@extends('layouts.app')
@section('page_title', 'Admin / Jadwal Lapangan')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Jadwal Pemanfaatan Lapangan (Admin View)
    </h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">Jadwal Lapangan 7 Hari ke Depan</h3>

    <div class="flex space-x-3 mb-6">
        @php 
            $filterNames = ['Futsal', 'Badminton', 'Voli', 'Basket'];
            $currentFilterKey = strstr($lapanganFilterName, ' ', true) ?: $lapanganFilterName; 
        @endphp

        @foreach ($filterNames as $lap)
            <a href="{{ route('admin.jadwal.index', ['lapangan' => $lap]) }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold border 
                      {{ $currentFilterKey == $lap 
                          ? 'bg-blue-500 text-white border-blue-500' 
                          : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
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

                                $lapKey = strstr($lapanganFilterName, ' ', true) ?: $lapanganFilterName;

                                if (isset($allBookings[$lapanganFilterName][$tanggal])) {
                                    foreach ($allBookings[$lapanganFilterName][$tanggal] as $booking) {
                                        if (
                                            $jamMulai >= $booking['jam_mulai'] &&
                                            $jamMulai < $booking['jam_selesai']
                                        ) {
                                            $isBooked = $booking;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <td class="px-3 py-2 text-sm text-center">
                                @if ($isBooked)
                                    @php
                                        $colorClass = 'bg-red-100 border-red-300 text-red-800 text-red-600';
                                        if (strtolower($isBooked['status']) === 'approved') {
                                            $colorClass = 'bg-green-100 border-green-300 text-green-800 text-green-600';
                                        } elseif (strtolower($isBooked['status']) === 'pending') {
                                            $colorClass = 'bg-yellow-100 border-yellow-300 text-yellow-800 text-yellow-600'; 
                                        }
                                        $classArray = explode(' ', $colorClass);
                                    @endphp

                                    <div class="p-1 rounded border text-center text-xs font-semibold {{ $classArray[0] }} {{ $classArray[1] }} {{ $classArray[2] }}">
                                        {{ $isBooked['nama'] }}
                                        <div class="text-xs italic {{ $classArray[3] }}">
                                            ({{ $isBooked['status'] }})
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">Kosong</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
        <strong>Catatan:</strong> Jadwal ditampilkan merupakan jadwal hingga 7 hari ke depan.
        Silakan perhatikan jadwal sebelum melakukan booking lapangan.
    </div>
</div>
@endsection
