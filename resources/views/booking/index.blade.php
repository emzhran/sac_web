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

        {{-- Alert Error (Opsional, untuk pesan error biasa) --}}
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg shadow-indigo-500/5 rounded-2xl border border-gray-100 p-4 md:p-6">

            {{-- Filter Lapangan (Horizontal Scroll di Mobile) --}}
            <div class="mb-6">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Pilih Lapangan:</label>
                <div class="flex overflow-x-auto pb-2 gap-3 no-scrollbar">
                    @php
                        $allLapangansData = \App\Models\Lapangan::all();
                        $uniqueNames = $allLapangansData->map(fn($lap) => explode(' ', trim($lap->nama))[0])->unique()->sort()->values();
                        $currentFilterBase = explode(' ', $lapanganFilterName)[0];

                        // Logic pencarian ID lapangan global
                        $foundLapangan = \App\Models\Lapangan::where('nama', $lapanganFilterName)
                            ->orWhere('nama', 'LIKE', $lapanganFilterName . '%')
                            ->first();
                        if (!$foundLapangan)
                            $foundLapangan = \App\Models\Lapangan::first();
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
                                <th scope="col"
                                    class="sticky left-0 z-20 w-20 min-w-[5rem] px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase border-r border-b border-gray-200 bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    Waktu
                                </th>

                                {{-- Header Hari --}}
                                @foreach ($dates as $date)
                                    <th scope="col" class="min-w-[100px] px-1 py-4 text-center text-xs font-bold uppercase tracking-wider border-b border-gray-200
                                            {{ $date->isToday() ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">
                                        <div class="flex flex-col">
                                            <span>{{ strtoupper($date->translatedFormat('D')) }}</span>
                                            <span
                                                class="text-[10px] font-normal opacity-80 mt-1">{{ $date->translatedFormat('d M') }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($timeSlots as $jamMulai)
                                @php
                                    $jamSelesai = sprintf('%02d:00', (int) substr($jamMulai, 0, 2) + 1);
                                @endphp
                                <tr class="group hover:bg-gray-50/30 transition-colors">

                                    {{-- Sticky Column Waktu --}}
                                    <td
                                        class="sticky left-0 z-10 px-2 py-3 text-center whitespace-nowrap text-xs font-bold text-gray-900 border-r border-gray-100 bg-white group-hover:bg-gray-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
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
                                            // $isPast = $date->isBefore(now()->startOfDay()) || ($date->isToday() && $jamMulai < now()->format('H:i'));
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
                                                <div
                                                                                class="w-full h-full flex flex-col items-center justify-center rounded-lg border {{ $colors }} p-1 cursor-not-allowed select-none">
                                                                                <span class="text-xs font-bold">{{ $icon }}</span>
                                                                                <span class="text-[9px] font-bold leading-tight truncate w-full text-center mt-0.5">
                                                                                    {{ \Illuminate\Support\Str::limit($isBooked['nama'], 8, '..') }}
                                                                                </span>
                                                                            </div>

                                            @elseif ($isPast)
                                                <div
                                                    class="w-full h-full bg-gray-50 rounded-lg flex items-center justify-center border border-transparent">
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
                                                                        <span
                                                                            class="text-indigo-400 font-bold text-lg group-hover:text-white leading-none">+</span>
                                                                        <span
                                                                            class="text-[9px] text-indigo-300 group-hover:text-indigo-100 font-medium">Book</span>
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

            {{-- Legend --}}
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

            <div class="mt-6 border-t border-gray-100 pt-6">
                <div
                    class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 flex flex-col md:flex-row gap-4 items-start">

                    {{-- Icon Info --}}
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Teks Panduan --}}
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Panduan Booking</h4>
                        <ul class="text-xs text-gray-600 space-y-2 leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0"></span>
                                <span>
                                    <strong>Navigasi Mobile:</strong> Jika menggunakan HP, geser tabel ke kanan/kiri untuk
                                    melihat jam atau hari yang terpotong.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0"></span>
                                <span>
                                    <strong>Cara Booking:</strong> Klik tombol <span
                                        class="inline-flex items-center justify-center px-1.5 py-0.5 rounded border border-indigo-200 bg-white text-indigo-600 font-bold text-[10px] mx-0.5 shadow-sm">+</span>
                                    pada kotak yang kosong (putih) untuk memesan jadwal tersebut.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 w-1 h-1 rounded-full bg-indigo-400 flex-shrink-0"></span>
                                <span>
                                    <strong>Jadwal Lewat:</strong> Kotak berwarna abu-abu (-) menandakan waktu sudah berlalu
                                    dan tidak dapat dipesan.
                                </span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MODAL SUSPEND (BAGIAN INI YANG DITAMBAHKAN) --}}
    {{-- ========================================================== --}}

    <div id="suspendModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{-- Overlay Gelap --}}
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Box Modal --}}
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">

                        {{-- Icon Gembok/Warning Merah --}}
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>

                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Akses Booking Ditangguhkan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">
                                    Mohon maaf, Anda tidak dapat melakukan pemesanan lapangan untuk sementara waktu.
                                </p>

                                <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-left">
                                    <p class="text-xs text-red-600 font-bold uppercase tracking-wider mb-1">Penyebab:</p>
                                    <p class="text-sm text-red-800 mb-3 font-medium">
                                        Terdeteksi 3x tidak konfirmasi kehadiran pada pesanan sebelumnya.
                                    </p>

                                    <div class="border-t border-red-200 pt-2 mt-2">
                                        <p class="text-xs text-red-600 font-bold uppercase tracking-wider mb-1">Dapat
                                            Booking Kembali:</p>
                                        <p class="text-xl font-extrabold text-red-700" id="suspendDateText">
                                            {{ session('show_suspend_modal') }}
                                        </p>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-400 mt-4">
                                    Hubungi administrator jika Anda merasa ini adalah kesalahan sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeSuspendModal()"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
        }

        // Cek Session dari Laravel Controller
        @if(session('show_suspend_modal'))
            document.addEventListener('DOMContentLoaded', function () {
                // Hapus class hidden agar modal muncul
                document.getElementById('suspendModal').classList.remove('hidden');
            });
        @endif
    </script>

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