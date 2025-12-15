@extends('layouts.app')
@section('page_title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    {{-- Header & Back Button --}}
    <div class="max-w-4xl mx-auto mb-8">
        <a href="{{ route('riwayat.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-4 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Riwayat
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-900">
                Detail Peminjaman <span class="text-indigo-600">#{{ $booking->id }}</span>
            </h1>
            
            {{-- Status Badge (Besar) --}}
            @php
                $statusStyles = match($booking->status) {
                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                };
                $statusLabel = match($booking->status) {
                    'approved' => 'Disetujui',
                    'pending' => 'Menunggu Konfirmasi',
                    'rejected' => 'Ditolak',
                    default => ucfirst($booking->status)
                };
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-bold border {{ $statusStyles }} flex items-center w-fit">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Informasi Utama (2/3 lebar) --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Card Informasi Lapangan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-start gap-4">
                    <div class="h-16 w-16 rounded-xl bg-indigo-50 flex items-center justify-center text-3xl shrink-0">
                        @if(Str::contains($booking->lapangan->nama ?? '', 'Futsal')) ⚽
                        @elseif(Str::contains($booking->lapangan->nama ?? '', 'Basket')) 🏀
                        @elseif(Str::contains($booking->lapangan->nama ?? '', 'Voli')) 🏐
                        @else 🏸 @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $booking->lapangan->nama ?? 'Nama Lapangan Tidak Tersedia' }}</h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $booking->lapangan->lokasi ?? 'Lokasi Kampus Terpadu UMY' }}</p>
                        <p class="text-gray-500 text-sm">Tipe: {{ $booking->lapangan->jenis ?? 'Olahraga' }}</p>
                    </div>
                </div>
                
                <div class="p-6 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Jadwal Main</h3>
                    <div class="grid gap-3">
                        @foreach($booking->jadwals as $jadwal)
                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="bg-indigo-100 p-2 rounded-md text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-md text-sm">
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Card Catatan (Opsional, jika ada kolom catatan di db) --}}
            @if(!empty($booking->catatan))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Catatan Peminjaman</h3>
                <p class="text-gray-600 bg-amber-50 p-4 rounded-xl border border-amber-100 text-sm">
                    "{{ $booking->catatan }}"
                </p>
            </div>
            @endif

        </div>

        {{-- Kolom Kanan: Aksi & Timeline (1/3 lebar) --}}
        <div class="space-y-6">
            
            {{-- Card Aksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Aksi</h3>
                
                <div class="space-y-3">
                    {{-- Tombol Download PDF --}}
                    @if($booking->status == 'approved')
                    <a href="{{ route('riwayat.pdf', $booking->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Bukti PDF
                    </a>
                    @endif

                    {{-- Tombol Konfirmasi Kehadiran --}}
                    @if($booking->status == 'approved')
                        @if(!$booking->confirmed_at)
                            <button onclick="openConfirmModal('{{ route('booking.confirm', $booking->id) }}')" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Konfirmasi Kehadiran
                            </button>
                            <p class="text-xs text-gray-500 text-center mt-2">Lakukan konfirmasi saat berada di lokasi.</p>
                        @else
                            <div class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 font-semibold rounded-xl border border-gray-200 cursor-default">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Sudah Hadir
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-1">Dikonfirmasi: {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('d M Y, H:i') }}</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Card Informasi Tambahan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Informasi</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between">
                        <span class="text-gray-500">Tanggal Pengajuan</span>
                        <span class="font-medium text-gray-900">{{ $booking->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Peminjam</span>
                        <span class="font-medium text-gray-900">{{ $booking->user->name }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Kontak</span>
                        <span class="font-medium text-gray-900">{{ $booking->user->nomor_hp ?? '-' }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>

{{-- INCLUDE MODAL & SCRIPT YANG SAMA DENGAN INDEX --}}
{{-- Modal Konfirmasi Kehadiran --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeConfirmModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Konfirmasi Kehadiran</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Sistem akan mendeteksi lokasi GPS Anda. Pastikan berada di area UMY.</p>
                            <p id="locationStatus" class="mt-3 text-sm font-semibold text-gray-700 hidden bg-gray-50 p-2 rounded">Memeriksa lokasi...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <form id="confirmForm" method="POST" action="">
                    @csrf
                    <button type="button" id="btnCheckLocation" onclick="checkLocationAndSubmit()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Cek Lokasi & Hadir</button>
                </form>
                <button type="button" onclick="closeConfirmModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GPS (Sama persis dengan index) --}}
<script>
    const UMY_LAT = -7.811345; 
    const UMY_LNG = 110.320745;
    const MAX_DISTANCE_KM = 0.5; 

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
            showError("Browser Anda tidak mendukung fitur GPS.");
            return;
        }

        navigator.geolocation.getCurrentPosition((position) => {
            validatePosition(position);
        }, (error) => {
            handleGpsError(error);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    }

    function validatePosition(position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const distance = calculateDistance(userLat, userLng, UMY_LAT, UMY_LNG);

        if (distance <= MAX_DISTANCE_KM) {
            const statusText = document.getElementById('locationStatus');
            statusText.textContent = `✅ Lokasi terverifikasi (${distance.toFixed(3)} km). Menyimpan...`;
            statusText.className = "mt-3 text-sm font-bold text-green-700 bg-green-100 p-2 rounded border border-green-300";
            setTimeout(() => { document.getElementById('confirmForm').submit(); }, 1000);
        } else {
            const statusText = document.getElementById('locationStatus');
            const jarakTerbaca = distance.toFixed(2);
            statusText.textContent = `⛔ Gagal. Jarak: ${jarakTerbaca} km dari UMY.`;
            statusText.className = "mt-3 text-sm font-bold text-red-700 bg-red-100 p-2 rounded border border-red-300";
            alert(`PERINGATAN:\n\nAnda berada di luar daerah UMY.\n(Jarak terdeteksi: ${jarakTerbaca} km).\n\nSilakan menuju ke lokasi.`);
            resetButton(document.getElementById('btnCheckLocation'));
        }
    }

    function handleGpsError(error) {
        showError("Gagal mendeteksi lokasi. Pastikan GPS aktif.");
        resetButton(document.getElementById('btnCheckLocation'));
    }

    function showError(message) {
        const statusText = document.getElementById('locationStatus');
        statusText.textContent = "❌ " + message;
        statusText.className = "mt-3 text-sm font-semibold text-red-700 bg-red-100 p-2 rounded border border-red-300";
    }

    function resetButton(btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = "Coba Lagi";
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; 
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) ** 2 + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) ** 2;
        return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
    }

    function deg2rad(deg) { return deg * (Math.PI/180); }
</script>
@endsection