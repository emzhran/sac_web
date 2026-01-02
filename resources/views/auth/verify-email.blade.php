@extends('layouts.app')

@section('page_title', 'Verifikasi Email')

@section('content')

<style>
    @keyframes scaleIn {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-scaleIn {
        animation: scaleIn 0.2s ease-out;
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-8 relative z-10">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email Anda</h2>
            <p class="text-sm text-gray-600">
                Sebelum melanjutkan, silakan verifikasi dengan mengklik link yang telah dikirim ke email Anda.
            </p>
        </div>

        <button onclick="openConfirmModal()"
            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            Kirim Ulang Email Verifikasi
        </button>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Logout dan Kembali ke halaman login
                </button>
            </form>
        </div>
    </div>
</div>

<div id="confirmModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 animate-scaleIn">
        <div class="text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0l-4-4m4 4l-4 4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Kirim ulang email verifikasi?</h3>
            <p class="text-sm text-gray-600 mb-6">Kami akan mengirim ulang link verifikasi ke email Anda.</p>
        </div>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                Batal
            </button>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Ya, Kirim
                </button>
            </form>
        </div>
    </div>
</div>

@if (session('status') == 'verification-link-sent')
<div id="successModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-8 animate-scaleIn text-center flex flex-col items-center">
        
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-2">Berhasil!</h3>
        <p class="text-sm text-gray-600 mb-8">
            Email verifikasi berhasil dikirim ulang ke email Anda.
        </p>
        
        <button onclick="closeSuccessModal()"
            class="w-full py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors shadow-sm">
            Tutup
        </button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function openConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        if(modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush