@extends('layouts.app')
@section('page_title', 'Admin / Jadwal Lapangan')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">
            Jadwal Pemanfaatan Lapangan
        </h1>
        <p class="text-sm text-gray-500">
            Pantau ketersediaan dan penggunaan lapangan untuk 7 hari ke depan (Admin View).
        </p>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white shadow-xl shadow-indigo-500/5 rounded-2xl border border-gray-100 p-6">
        
        <!-- Filter Tabs -->
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 mb-3 block">Filter Jenis Lapangan:</label>
            <div class="flex flex-wrap gap-3">
                @php
                    $allLapangans = $allLapangans ?? \App\Models\Lapangan::all(); 
                    $lapanganGroups = $allLapangans->groupBy(fn($lap) => explode(' ', $lap->nama)[0]);
                    $currentFilterBase = explode(' ', $lapanganFilterName)[0];
                @endphp

                @foreach ($lapanganGroups as $lapTypeName => $lapList)
                    @php
                        $targetLapName = $lapList->first()->nama; 
                        $isActive = $currentFilterBase == $lapTypeName;
                    @endphp

                    <a href="{{ route('admin.jadwal.index', ['lapangan' => $targetLapName]) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition-all duration-200
                              {{ $isActive 
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30' 
                                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $lapTypeName }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 bg-gray-50 sticky left-0 z-10">
                                Waktu
                            </th>
                            @foreach ($dates as $date)
                                <th scope="col" 
                                    class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider min-w-[140px]
                                           {{ $date->isToday() ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">
                                    <div class="flex flex-col">
                                        <span>{{ $date->translatedFormat('D') }}</span>
                                        <span class="text-xs font-normal opacity-80">{{ $date->translatedFormat('d M') }}</span>
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
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100 bg-gray-50/30 sticky left-0 z-10">
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
                                        $isPast = $date->isBefore(now()->startOfDay());
                                    @endphp

                                    <td class="px-2 py-2 text-center align-middle border-l border-dashed border-gray-100">
                                        @if ($isBooked)
                                            {{-- TAMPILAN JIKA SUDAH DIBOOKING --}}
                                            @php
                                                $status = strtolower($isBooked['status']);
                                                $styles = match ($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                                    'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                                    default => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                                };
                                            @endphp
                                            <div class="flex flex-col items-center justify-center p-2 rounded-lg {{ $styles }}">
                                                <span class="text-xs font-bold truncate max-w-[120px]">{{ $isBooked['nama'] }}</span>
                                                <span class="text-[10px] uppercase tracking-wide opacity-80 font-semibold">({{ ucfirst($status) }})</span>
                                            </div>

                                        @elseif ($isPast)
                                            {{-- TAMPILAN WAKTU LAMPAU --}}
                                            <div class="h-full w-full py-2 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center opacity-50">
                                                 <span class="text-gray-400 text-xs italic">Lewat</span>
                                            </div>

                                        @else
                                            {{-- TAMPILAN SLOT KOSONG (Admin biasanya hanya memantau) --}}
                                            <div class="h-full w-full py-2 flex items-center justify-center">
                                                <span class="text-gray-300 text-xs">-</span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-6 flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm leading-relaxed">
                <strong>Catatan Admin:</strong> 
                Jadwal ini menampilkan okupansi lapangan untuk 7 hari ke depan.
                Slot berwarna <span class="text-emerald-600 font-bold">Hijau</span> adalah booking yang sudah disetujui (Approved), sedangkan <span class="text-amber-600 font-bold">Kuning</span> adalah booking yang masih menunggu persetujuan (Pending).
            </div>
        </div>
    </div>
</div>
@endsection