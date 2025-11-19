@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Dashboard
</h2>
@endsection

@section('content')
<main class="flex-1 p-6">
    @if (session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white shadow-md p-16 rounded-lg">
            <h3 class="text-2xl font-bold text-blue-800">Lapangan Futsal</h3>
            <p class="text-gray-500 mt-2">Informasi pemesanan lapangan futsal.</p>
        </div>

        <div class="bg-white shadow-md p-6 rounded-lg md:col-span-1 md:row-span-2">
            <h3 class="text-2xl font-bold text-blue-800">Informasi Sport Center</h3>
            <p class="text-gray-500 mt-2">Detail fasilitas dan jadwal ketersediaan lapangan.</p>
        </div>

        <div class="bg-white shadow-md p-16 rounded-lg">
            <h3 class="text-2xl font-bold text-blue-800">Lapangan Voli</h3>
            <p class="text-gray-500 mt-2">Informasi pemesanan lapangan voli.</p>
        </div>
    </div>
</main>
@endsection