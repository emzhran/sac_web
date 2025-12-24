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

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

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
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$r->confirmed_at)
                                        @php
                                            $firstJadwal = $r->jadwals->first();
                                            $tanggalMain = $firstJadwal ? $firstJadwal->tanggal : null;
                                            $jamMulai = $firstJadwal ? $firstJadwal->jam_mulai : null;
                                            
                                            $deadlineString = null;
                                            $isAlreadyLate = false;

                                            if ($tanggalMain && $jamMulai) {
                                                $bookingTime = \Carbon\Carbon::parse($tanggalMain . ' ' . $jamMulai);
                                                $deadlineTime = $bookingTime->copy()->addHour();
                                                $deadlineString = $deadlineTime->format('Y-m-d H:i:s'); 
                                                if (\Carbon\Carbon::now()->gt($deadlineTime)) {
                                                    $isAlreadyLate = true;
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="booking-status-wrapper" data-deadline="{{ $deadlineString }}">
                                            
                                            <span class="status-late inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200 {{ $isAlreadyLate ? '' : 'hidden' }}">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Tidak Terkonfirmasi
                                            </span>

                                            <button onclick="checkTimeAndOpenModal('{{ route('booking.confirm', $r->id) }}', '{{ $tanggalMain }}', '{{ $jamMulai }}')"
                                               class="status-active inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 {{ $isAlreadyLate ? 'hidden' : '' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Hadir
                                            </button>
                                        </div>

                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded border border-gray-200">
                                            <svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terkonfirmasi
                                        </span>
                                    @endif

                                    <a href="{{ route('riwayat.show', $r->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-gray-700 text-xs font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                </div>
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

<div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeConfirmModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Konfirmasi Kehadiran</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Sistem akan mendeteksi lokasi GPS Anda. Fitur ini hanya dapat digunakan jika Anda berada di area <b>Universitas Muhammadiyah Yogyakarta</b>.</p>
                            <p id="locationStatus" class="mt-3 text-sm font-semibold text-gray-700 hidden bg-gray-50 p-2 rounded">Memeriksa lokasi...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <form id="confirmForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="latitude" id="inputLat">
                    <input type="hidden" name="longitude" id="inputLng">

                    <button type="button" id="btnCheckLocation" onclick="checkLocationAndSubmit()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Cek Lokasi & Hadir</button>
                </form>
                <button type="button" onclick="closeConfirmModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="errorModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeErrorModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Di Luar Jangkauan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Maaf, Anda terdeteksi berada di luar area Universitas Muhammadiyah Yogyakarta.</p>
                            <div class="mt-4 bg-red-50 rounded-lg p-3 border border-red-100">
                                <p class="text-xs text-red-700 font-semibold">Jarak Terdeteksi: <span id="userDistance" class="text-lg font-bold">0</span> KM</p>
                                <p class="text-xs text-red-500 mt-1">Maksimal Jarak: 0.5 KM</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">Silakan mendekat ke lokasi lapangan untuk melakukan konfirmasi kehadiran.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeErrorModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<div id="earlyAccessModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEarlyAccessModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Belum Waktunya</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Konfirmasi kehadiran hanya dapat dilakukan <b>minimal 1 jam</b> sebelum pemakaian lapangan.
                            </p>
                            <div class="mt-4 bg-amber-50 rounded-lg p-3 border border-amber-100">
                                <p class="text-xs text-amber-700 font-semibold">
                                    Jadwal Main: <span id="scheduleInfo" class="font-bold"></span>
                                </p>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">
                                Silakan kembali lagi nanti mendekati jam main.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeEarlyAccessModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<div id="messageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMessageModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="msgModalTitle">Pesan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="msgModalBody"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeMessageModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const UMY_LAT = -7.811345; 
    const UMY_LNG = 110.320745;
    const MAX_DISTANCE_KM = 0.5; 

    function updateBookingStatuses() {
        const statusWrappers = document.querySelectorAll('.booking-status-wrapper');
        const now = new Date();

        statusWrappers.forEach(wrapper => {
            const deadlineString = wrapper.getAttribute('data-deadline');
            
            if (!deadlineString) return;
            const isoString = deadlineString.replace(' ', 'T'); 
            const deadlineDate = new Date(isoString);

            if (now > deadlineDate) {
                const btnHadir = wrapper.querySelector('.status-active');
                const badgeLate = wrapper.querySelector('.status-late');

                if (btnHadir && !btnHadir.classList.contains('hidden')) {
                    btnHadir.classList.add('hidden');
                }
                if (badgeLate && badgeLate.classList.contains('hidden')) {
                    badgeLate.classList.remove('hidden');
                }
            }
        });
    }

    setInterval(updateBookingStatuses, 1000);
    updateBookingStatuses();

    function showMessageModal(title, body) {
        document.getElementById('msgModalTitle').innerText = title;
        document.getElementById('msgModalBody').innerText = body;
        document.getElementById('messageModal').classList.remove('hidden');
    }

    function closeMessageModal() {
        document.getElementById('messageModal').classList.add('hidden');
    }

    function checkTimeAndOpenModal(url, dateStr, timeStr) {
        if(!dateStr || !timeStr) {
            openConfirmModal(url);
            return;
        }

        const dateTimeStr = (dateStr + 'T' + timeStr);
        const bookingDate = new Date(dateTimeStr);
        const now = new Date();
        const oneHourInMillis = 60 * 60 * 1000; 
        
        const allowedCheckInTime = new Date(bookingDate.getTime() - oneHourInMillis);

        if (now < allowedCheckInTime) {
            document.getElementById('scheduleInfo').innerText = `${dateStr} jam ${timeStr}`;
            document.getElementById('earlyAccessModal').classList.remove('hidden');
        } else {
            openConfirmModal(url);
        }
    }

    function closeEarlyAccessModal() {
        document.getElementById('earlyAccessModal').classList.add('hidden');
    }

    function openConfirmModal(url) {
        document.getElementById('confirmForm').action = url;
        document.getElementById('confirmModal').classList.remove('hidden');

        const statusText = document.getElementById('locationStatus');
        const btn = document.getElementById('btnCheckLocation');

        statusText.classList.add('hidden');
        statusText.textContent = "";
        
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = "Cek Lokasi & Hadir";
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
    }

    function checkLocationAndSubmit() {
        const statusText = document.getElementById('locationStatus');
        const btn = document.getElementById('btnCheckLocation');

        statusText.classList.remove('hidden');
        statusText.textContent = "📍 Sedang mendeteksi lokasi Anda...";
        statusText.className = "mt-3 text-sm font-semibold text-blue-600 animate-pulse bg-blue-50 p-2 rounded border border-blue-200";

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = "Memproses...";

        if (!navigator.geolocation) {
            closeConfirmModal();
            showMessageModal("Browser Tidak Mendukung", "Browser Anda tidak mendukung fitur GPS.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => { validatePosition(position); },
            (error) => { handleGpsError(error); },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function validatePosition(position) {
        const statusText = document.getElementById('locationStatus');
        const btn = document.getElementById('btnCheckLocation');
        
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const distance = calculateDistance(userLat, userLng, UMY_LAT, UMY_LNG);
        const jarakTerbaca = distance.toFixed(3);

        if (distance <= MAX_DISTANCE_KM) {
            statusText.textContent = `✅ Lokasi terverifikasi (Jarak: ${jarakTerbaca} km). Menyimpan...`;
            statusText.className = "mt-3 text-sm font-bold text-green-700 bg-green-100 p-2 rounded border border-green-300";
            setTimeout(() => { document.getElementById('confirmForm').submit(); }, 1000);
        } else {
            closeConfirmModal();
            document.getElementById('userDistance').innerText = jarakTerbaca;
            document.getElementById('errorModal').classList.remove('hidden');
            resetButton(btn);
        }
    }

    function handleGpsError(error) {
        const btn = document.getElementById('btnCheckLocation');
        let msg = "Terjadi kesalahan GPS.";
        if (error.code === error.PERMISSION_DENIED) msg = "Akses lokasi DITOLAK. Mohon izinkan akses lokasi di browser Anda.";
        if (error.code === error.POSITION_UNAVAILABLE) msg = "Sinyal GPS tidak ditemukan.";
        if (error.code === error.TIMEOUT) msg = "Waktu permintaan lokasi habis.";
        
        closeConfirmModal();
        showMessageModal("Kesalahan GPS", msg);
        resetButton(btn);
    }

    function resetButton(btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = "Cek Lokasi & Hadir";
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; 
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2)**2 + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2)**2;
        return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
    }
    function deg2rad(deg) { return deg * (Math.PI/180); }
</script>

@endsection