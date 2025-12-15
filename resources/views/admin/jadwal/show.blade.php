@extends('layouts.app')
@section('page_title', 'Admin / Detail Jadwal Booking')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Jadwal Booking
    </h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-md mx-auto space-y-4">

    <div>
        <label class="block font-medium text-gray-700">Lapangan</label>
        <input type="text" value="{{ $lapangan->nama }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Tanggal</label>
        <input type="date" value="{{ $jadwal->tanggal }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Jam Mulai</label>
        <input type="text" value="{{ $jadwal->jam_mulai }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Jam Selesai</label>
        <input type="text" value="{{ $jadwal->jam_selesai }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Nama Pemesan</label>
        <input type="text" value="{{ $booking->nama_pemesan }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Status</label>
        <input type="text" value="{{ ucfirst($booking->status) }}" readonly
               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
    </div>

    <div>
      <form action="{{ route('admin.booking.update_status', $booking->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <label for="status" class="block font-medium text-gray-700 mb-1">Ubah Status:</label>
        <select name="status" id="status" class="border rounded p-2 w-full">
            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Update Status
        </button>
     </form>
    <div>
        <a href="{{ route('admin.jadwal.index') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Kembali ke Daftar Jadwal
        </a>
    </div>
</div>
@endsection