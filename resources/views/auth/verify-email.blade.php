@extends('layouts.app')
@section('page_title', 'Verifikasi Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white shadow-lg rounded-xl p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email Anda</h2>
            <p class="text-gray-600 text-sm">
                Sebelum melanjutkan, silakan verifikasi dengan mengklik link yang telah dikirim ke email Anda.
            </p>
        </div>

        @if (session('message'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 text-sm text-center">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" 
                class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline focus:outline-none">
                    Logout dan Kembali ke halaman login
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
