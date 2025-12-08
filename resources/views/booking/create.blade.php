@extends('layouts.app')
@section('page_title', 'Form Peminjaman')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen flex justify-center items-start">

    <div class="w-full max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Form Booking Lapangan
            </h1>
            <p class="text-sm text-gray-500">
                Lengkapi data di bawah ini untuk mengajukan peminjaman.
            </p>
        </div>

        <div class="bg-white shadow-xl shadow-indigo-500/10 rounded-2xl border border-gray-100 overflow-hidden">

            <div class="bg-indigo-600 p-6 flex items-center justify-between text-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <span class="text-2xl">
                            @if(Str::contains($lapangan->nama, 'Futsal')) ⚽
                            @elseif(Str::contains($lapangan->nama, 'Basket')) 🏀
                            @elseif(Str::contains($lapangan->nama, 'Voli')) 🏐
                            @else 🏸 @endif
                        </span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Booking Lapangan {{ $lapangan->nama }}</h2>
                    </div>
                </div>
            </div>

            <form id="bookingForm" action="{{ route('booking.store', ['lapangan' => $lapangan->id]) }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Mahasiswa
                            </label>
                            <input type="text" value="{{ Auth::user()->name }}" readonly
                                class="w-full border-gray-200 bg-gray-50 text-gray-500 rounded-xl px-4 py-3 focus:ring-0 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                NIM Mahasiswa
                            </label>
                            <input type="text" value="{{ Auth::user()->nim }}" readonly
                                class="w-full border-gray-200 bg-gray-50 text-gray-500 rounded-xl px-4 py-3 focus:ring-0 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Booking
                            </label>
                            <input type="date" name="tanggal" value="{{ $tanggal }}" readonly
                                class="w-full border-gray-200 bg-gray-50 text-gray-500 rounded-xl px-4 py-3 focus:ring-0 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center">
                        <label class="block text-sm font-bold text-gray-800 mb-6 text-center uppercase tracking-wider">Durasi Peminjaman</label>
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full border border-amber-200">
                            MAKS. 3 JAM
                        </span>
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div class="flex-1">
                                <label class="text-xs text-gray-500 block mb-1 text-center">Mulai</label>
                                <div class="relative">
                                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ $jam_mulai }}" readonly
                                        class="w-full text-center border-0 bg-white shadow-sm rounded-xl py-3 text-lg font-bold text-gray-700 focus:ring-0">
                                </div>
                            </div>

                            <div class="text-gray-400 font-bold">-</div>

                            <div class="flex-1">
                                <label class="text-xs text-gray-500 block mb-1 text-center">Selesai</label>
                                <div class="relative">
                                    <input type="time" id="jam_selesai" name="jam_selesai" readonly
                                        class="w-full text-center border-0 bg-white shadow-sm rounded-xl py-3 text-lg font-bold text-indigo-600 focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center gap-4">
                            <button type="button" id="minusBtn"
                                class="w-12 h-12 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all flex items-center justify-center shadow-sm active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>

                            <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">Ubah Durasi</span>

                            <button type="button" id="plusBtn"
                                class="w-12 h-12 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all flex items-center justify-center shadow-sm active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('booking.index', ['lapangan' => explode(' ', $lapangan->nama)[0]]) }}"
                        class="px-6 py-3 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                        Batal & Kembali
                    </a>

                    <button id="submitBtn" type="button"
                        class="px-8 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        Ajukan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    function pad(num) {
        return String(num).padStart(2, '0');
    }

    function updateButtonState() {
        let mulai = document.getElementById('jam_mulai').value;
        let selesai = document.getElementById('jam_selesai').value;

        let [jamMulai] = mulai.split(':').map(Number);
        let [jamSelesai] = selesai.split(':').map(Number);

        const plusBtn = document.getElementById('plusBtn');
        const minusBtn = document.getElementById('minusBtn');

        if (jamSelesai >= jamMulai + 3 || jamSelesai >= 23) {
            plusBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            plusBtn.classList.remove('hover:bg-emerald-50', 'hover:text-emerald-600', 'hover:border-emerald-200');
        } else {
            plusBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            plusBtn.classList.add('hover:bg-emerald-50', 'hover:text-emerald-600', 'hover:border-emerald-200');
        }

        if (jamSelesai <= jamMulai + 1) {
            minusBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            minusBtn.classList.remove('hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
        } else {
            minusBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
            minusBtn.classList.add('hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
        }
    }

    function updateJamSelesai() {
        let mulai = document.getElementById('jam_mulai').value;
        let selesaiInput = document.getElementById('jam_selesai');
        if (mulai) {
            let [jamMulai] = mulai.split(':').map(Number);
            selesaiInput.value = pad(jamMulai + 1) + ":00";
            updateButtonState();
        }
    }

    window.onload = updateJamSelesai;

    document.getElementById('plusBtn').onclick = function() {
        let mulai = document.getElementById('jam_mulai').value;
        let selesai = document.getElementById('jam_selesai').value;

        let [jamMulai] = mulai.split(':').map(Number);
        let [jamSelesai] = selesai.split(':').map(Number);

        if (jamSelesai >= jamMulai + 3) {
            Toast.fire({
                icon: 'warning',
                title: 'Maksimal durasi hanya 3 jam'
            });
            return;
        }

        if (jamSelesai >= 23) {
            Toast.fire({
                icon: 'warning',
                title: 'Operasional tutup jam 23:00'
            });
            return;
        }

        jamSelesai++;
        document.getElementById('jam_selesai').value = pad(jamSelesai) + ":00";
        updateButtonState();
    };

    document.getElementById('minusBtn').onclick = function() {
        let mulai = document.getElementById('jam_mulai').value;
        let selesai = document.getElementById('jam_selesai').value;
        let [jamMulai] = mulai.split(':').map(Number);
        let [jamSelesai] = selesai.split(':').map(Number);

        if (jamSelesai <= jamMulai + 1) return;

        jamSelesai--;
        document.getElementById('jam_selesai').value = pad(jamSelesai) + ":00";
        updateButtonState();
    };

    document.getElementById('submitBtn').addEventListener('click', function() {
        const currentJamMulai = document.getElementById('jam_mulai').value;
        const currentJamSelesai = document.getElementById('jam_selesai').value;

        Swal.fire({
            title: 'Konfirmasi Booking',
            html: `
                <div class="text-left bg-gray-50 p-4 rounded-lg text-sm mb-4">
                    <p class="mb-2"><strong class="text-gray-700">Lapangan:</strong> {{ $lapangan->nama }}</p>
                    <p class="mb-2"><strong class="text-gray-700">Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                    <p><strong class="text-gray-700">Waktu:</strong> <span class="text-indigo-600 font-bold">${currentJamMulai} - ${currentJamSelesai}</span></p>
                </div>
                <p class="text-gray-600 text-sm">Pastikan jadwal sudah sesuai sebelum melanjutkan.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Booking Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#9ca3af',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-medium'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bookingForm').submit();
            }
        });
    });
</script>
@endsection