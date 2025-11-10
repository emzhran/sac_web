@extends('layouts.app')
@section('page_title', 'Pages / ' . $lapangan->nama) 

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Form Peminjaman Lapangan {{ $lapangan->nama }}
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Form Peminjaman Lapangan {{ $lapangan->nama }}</h2>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4 rounded-md">
        <strong>Perhatian!</strong><br>
        Jam sudah terisi otomatis dari jadwal yang Anda pilih, pastikan Anda telah memilih jam dan tanggal yang benar.
    </div>

    <form id="bookingForm" action="{{ route('booking.store', ['lapangan' => $lapangan->id]) }}" method="POST" class="space-y-4">
        @csrf
        
        <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nama Pemohon</label>
            <p class="w-full border border-gray-300 bg-gray-100 rounded-md px-3 py-2 text-gray-800 font-semibold">
                {{ Auth::user()->name }}
            </p>
        </div>
        
        <div>
            <label for="tanggal_booking" class="block text-gray-700 font-medium mb-1">Tanggal Peminjaman</label>
            <input type="date" name="tanggal_booking" id="tanggal_booking" required readonly
                value="{{ $tanggal }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-800 font-medium">
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label for="jam_mulai" class="block text-gray-700 font-medium mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" required readonly
                    value="{{ $jam_mulai }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-800 font-medium">
            </div>
            <div class="flex-1">
                <label for="jam_selesai" class="block text-gray-700 font-medium mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" required readonly
                    value="{{ $jam_selesai }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-800 font-medium">
            </div>
        </div>

        <div class="flex pt-4 space-x-3">
            <a href="{{ route('booking.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-md transition">
                Kembali
            </a>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-md transition">
                Ajukan
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const form = document.getElementById('bookingForm'); 

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const lapanganId = document.querySelector('input[name="lapangan_id"]').value;
        const tanggalBooking = document.getElementById('tanggal_booking').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        if (!lapanganId || !tanggalBooking || !jamMulai || !jamSelesai) {
             return Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Harap isi semua data peminjaman!',
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

        const formData = new FormData(form);
        
        fetch(form.action, { method: 'POST', body: formData })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(({status, body}) => {
                if (status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: body.message || 'Pemesanan lapangan berhasil diajukan!',
                        confirmButtonColor: '#0ea5e9'
                    }).then(() => {
                        window.location.href = "{{ route('booking.index') }}";
                    });
                } else {
                    let errorMessage = body.message || 'Terjadi kesalahan saat memproses pemesanan.';
                    if (body.errors) {
                        errorMessage = Object.values(body.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
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
