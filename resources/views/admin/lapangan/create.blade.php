@extends('layouts.app')

@section('page_title', 'Admin / Tambah Lapangan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">Tambahkan Lapangan Baru</h3>

    <form method="POST" action="{{ route('admin.lapangan.store') }}" class="space-y-6">
        @csrf

        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lapangan</label>
            <input type="text" name="nama" id="nama"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required value="{{ old('nama') }}" placeholder="Contoh: Futsal, Badminton">

            @error('nama')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4" role="alert">
            <p class="font-bold">Informasi Lapangan</p>
            <p class="text-sm">Masukan nama lapangan baru.</p>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.lapangan.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-2 rounded-md transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Tambah Lapangan
            </button>
        </div>
    </form>
</div>
@endsection