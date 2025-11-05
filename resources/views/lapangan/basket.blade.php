@extends('layouts.app')
@section('page_title', 'Pages / Basket')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Form Peminjaman Lapangan Basket
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Form Peminjaman Lapangan Basket</h2>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4 rounded-md">
        <strong>Perhatian!</strong><br>
        Pastikan Anda memilih jam mulai dan jam selesai dengan menit <b>00</b> 
        (misalnya 10:00–11:00, bukan 10:10–11:00).
    </div>

    <form id="basketForm" action="{{ route('basket.store') }}" method="POST" class="space-y-4">
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
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-sky-400">
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label for="jam_mulai" class="block text-gray-700 font-medium mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-sky-400">
            </div>
            <div class="flex-1">
                <label for="jam_selesai" class="block text-gray-700 font-medium mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-sky-400">
            </div>
        </div>

        <div class="flex pt-4">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-md transition">
                Ajukan
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const form = document.getElementById('basketForm');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const jadwalDate = document.getElementById('jadwal_date').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        if (!jadwalDate || !jamMulai || !jamSelesai) {
            return Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Harap isi semua data waktu peminjaman!',
                confirmButtonColor: '#0ea5e9'
            });
        }

        const menitMulai = jamMulai.split(':')[1];
        const menitSelesai = jamSelesai.split(':')[1];

        if (menitMulai !== '00' || menitSelesai !== '00') {
            return Swal.fire({
                icon: 'warning',
                title: 'Format Jam Salah',
                text: 'Jam mulai dan selesai harus diakhiri dengan menit 00 (contoh: 10:00 - 11:00).',
                confirmButtonColor: '#facc15'
            });
        }

        if (jamMulai >= jamSelesai) {
            return Swal.fire({
                icon: 'warning',
                title: 'Jam Tidak Valid',
                text: 'Jam selesai harus setelah jam mulai.',
                confirmButtonColor: '#f43f5e'
            });
        }

        const fullJadwal = `${jadwalDate} ${jamMulai}:00`;
        const formData = new FormData(form);
        formData.set('jadwal', fullJadwal);
        formData.set('jam_selesai', jamSelesai);

        fetch(form.action, { method: 'POST', body: formData })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(({status, body}) => {
                if (status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: body.message || 'Pemesanan lapangan basket berhasil diajukan!',
                        confirmButtonColor: '#0ea5e9'
                    });
                    form.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: body.message || 'Lapangan sudah dibooking atau terjadi kesalahan.',
                        confirmButtonColor: '#f43f5e'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengirim data, silakan coba lagi.',
                    confirmButtonColor: '#f43f5e'
                });
            });
    });
</script>
@endsection
