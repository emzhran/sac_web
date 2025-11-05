@extends('layouts.app')
@section('page_title', 'Pages / Voli')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Form Peminjaman Lapangan Voli
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Form Peminjaman Lapangan Voli</h2>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4 rounded-md">
        <strong>Perhatian!</strong><br>
        Pastikan Anda memilih jam mulai dan jam selesai dengan menit <b>00</b> (misalnya 10:00–11:00, bukan 10:10–11:00).
    </div>

    <form id="voliForm" action="{{ route('voli.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="status" value="pending">

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nama Pemohon</label>
            <p class="w-full border border-gray-300 bg-gray-500 rounded-md px-3 py-2 text-white font-semibold">
                {{ Auth::user()->name }}
            </p>
            <input type="hidden" name="nama" id="nama" value="{{ Auth::user()->name }}">
        </div>

        <div>
            <label for="jadwal_date" class="block text-gray-700 font-medium mb-1">Tanggal Peminjaman</label>
            <input type="date" name="jadwal_date" id="jadwal_date"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label for="jam_mulai" class="block text-gray-700 font-medium mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>
            <div class="flex-1">
                <label for="jam_selesai" class="block text-gray-700 font-medium mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex pt-4"> 
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-md transition duration-150 ease-in-out">
                Ajukan
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const form = document.getElementById('voliForm');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const jadwalDate = document.getElementById('jadwal_date').value;
    const jamMulai = document.getElementById('jam_mulai').value;
    const jamSelesai = document.getElementById('jam_selesai').value;

    if (!jadwalDate || !jamMulai || !jamSelesai) {
        Swal.fire({
            icon: 'warning',
            title: 'Form Belum Lengkap',
            text: 'Harap isi semua data waktu peminjaman!',
            confirmButtonColor: '#0ea5e9'
        });
        return;
    }

    if (jamMulai.slice(-2) !== '00' || jamSelesai.slice(-2) !== '00') {
        Swal.fire({
            icon: 'error',
            title: 'Format Jam Tidak Valid',
            text: 'Jam mulai dan jam selesai harus berakhir dengan :00 (contoh 10:00 – 11:00).',
            confirmButtonColor: '#f43f5e'
        });
        return;
    }

    const fullJadwal = `${jadwalDate} ${jamMulai}:00`;
    const formData = new FormData(form);
    formData.set('jadwal', fullJadwal);
    formData.set('jam_selesai', jamSelesai);

    fetch(form.action, { method: 'POST', body: formData })
        .then(async response => {
            const data = await response.json();
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#0ea5e9'
                });
                form.reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#f43f5e'
                });
            }
        })
        .catch(err => console.error(err));
});
</script>
@endsection
