@extends('layouts.app')

@section('page_title', 'Profil Saya')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">
            Pengaturan Akun
        </h1>
        <p class="text-sm text-gray-500">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white shadow-lg shadow-indigo-500/10 rounded-2xl border border-gray-100 p-8">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white shadow-lg shadow-indigo-500/10 rounded-2xl border border-gray-100 p-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- 
            <div class="bg-red-50 shadow-lg shadow-red-500/10 rounded-2xl border border-red-100 p-8">
                @include('profile.partials.delete-user-form')
            </div> 
            --}}
        </div>
    </div>
</div>
@endsection