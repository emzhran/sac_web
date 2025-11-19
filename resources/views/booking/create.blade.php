@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Form Peminjaman Lapangan {{ $lapangan->nama }}
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Form Peminjaman Lapangan {{ $lapangan->nama }}</h2>

    <form id="bookingForm" action="{{ route('booking.store', ['lapangan' => $lapangan->id]) }}" method="POST" class="space-y-4">
        @csrf

        <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nama Pemohon</label>
            <p class="w-full border border-gray-300 bg-gray-100 rounded-md px-3 py-2 font-semibold">
                {{ Auth::user()->name }}
            </p>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Tanggal Booking</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" readonly
                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Jam Mulai</label>
            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ $jam_mulai }}" readonly
                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Jam Selesai</label>
            <div class="flex items-center gap-2">
                <button type="button" id="minusBtn" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">−</button>
                <input type="time" id="jam_selesai" name="jam_selesai"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                <button type="button" id="plusBtn" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">+</button>
            </div>
            <p class="text-sm text-gray-500 mt-1">Gunakan tombol + / − untuk mengubah durasi bermain.</p>
        </div>

        <div class="flex pt-4 space-x-3">
            <a href="{{ route('booking.index', ['lapangan' => explode(' ', $lapangan->nama)[0]]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-md">
               Kembali
            </a>

            <button id="submitBtn" type="button"
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-md">
               Ajukan
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function pad(num) {
    return String(num).padStart(2, '0');
}

function updateJamSelesai() {
    let mulai = document.getElementById('jam_mulai').value;
    let selesaiInput = document.getElementById('jam_selesai');
    let [jamMulai] = mulai.split(':').map(Number);
    selesaiInput.value = pad(jamMulai + 1) + ":00";
}

window.onload = updateJamSelesai;

document.getElementById('plusBtn').onclick = function() {
    let mulai = document.getElementById('jam_mulai').value;
    let selesai = document.getElementById('jam_selesai').value;
    let [jamMulai] = mulai.split(':').map(Number);
    let [jamSelesai] = selesai.split(':').map(Number);

    jamSelesai++;
    if (jamSelesai >= 23) jamSelesai = 23;
    document.getElementById('jam_selesai').value = pad(jamSelesai) + ":00";
};

document.getElementById('minusBtn').onclick = function() {
    let mulai = document.getElementById('jam_mulai').value;
    let selesai = document.getElementById('jam_selesai').value;
    let [jamMulai] = mulai.split(':').map(Number);
    let [jamSelesai] = selesai.split(':').map(Number);

    if (jamSelesai <= jamMulai + 1) return;
    jamSelesai--;
    document.getElementById('jam_selesai').value = pad(jamSelesai) + ":00";
};

document.getElementById('submitBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Konfirmasi Booking',
        html: `Apakah Anda yakin ingin memesan lapangan <b>{{ $lapangan->nama }}</b> pada tanggal <b>{{ $tanggal }}</b> jam <b>{{ $jam_mulai }} - <span id="saJamSelesai">{{ $jam_selesai ?? '' }}</span></b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, ajukan!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#f43f5e',
        preConfirm: () => {
            document.getElementById('bookingForm').submit();
        }
    });

    const selesaiInput = document.getElementById('jam_selesai');
    selesaiInput.addEventListener('input', () => {
        document.getElementById('saJamSelesai').textContent = selesaiInput.value;
    });
});
</script>
@endsection
