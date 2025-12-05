@extends('layouts.app')

@section('page_title', 'Dashboard Admin')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <!-- Header Section -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Dashboard Admin
            </h1>
            <p class="text-sm text-gray-500">Laporan Periode: <span class="font-semibold text-indigo-600">{{ $periodLabel }}</span></p>
        </div>

        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
            <!-- Grouped Input Filter -->
            <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden divide-x divide-gray-100">
                
                <!-- Filter Tanggal -->
                <select name="day" class="border-none py-2.5 pl-4 pr-8 text-sm text-gray-600 bg-transparent focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors">
                    <option value="">Harian</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>

                <!-- Filter Bulan -->
                <select name="month" class="border-none py-2.5 pl-4 pr-8 text-sm text-gray-600 bg-transparent focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors">
                    @foreach($months as $key => $label)
                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <!-- Filter Tahun -->
                <select name="year" class="border-none py-2.5 pl-4 pr-8 text-sm text-gray-600 bg-transparent focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <!-- Tombol Submit Icon -->
                <button type="submit" class="px-4 py-2.5 bg-gray-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 flex items-center justify-center" title="Terapkan Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Tombol Reset (Muncul jika ada filter aktif atau selalu muncul sebagai opsi refresh) -->
            <a href="{{ url()->current() }}" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200" title="Reset Filter">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </a>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Booking Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/30 p-6 rounded-2xl relative overflow-hidden group hover:shadow-xl hover:shadow-indigo-500/40 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
            
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full text-right truncate max-w-[120px]">{{ $periodLabel }}</span>
                </div>
                <p class="text-sm font-medium opacity-90 mb-1">Total Booking</p>
                <p class="text-4xl font-bold">{{ $totalBookings ?? 0 }}</p>
            </div>
        </div>

        <!-- Most Used Field Card -->
        <div class="bg-white shadow-lg p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-2">Paling Banyak Digunakan</p>
                <p class="text-2xl font-bold text-gray-900 mb-1 truncate" title="{{ $mostUsedField }}">{{ $mostUsedField ?? 'N/A' }}</p>
                <p class="text-xs text-gray-400">Periode {{ $periodLabel }}</p>
            </div>
        </div>
        
        <!-- Pending Bookings Card -->
        <div class="bg-white shadow-lg p-6 rounded-2xl border border-gray-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if(($pendingBookings ?? 0) > 0)
                    <span class="flex items-center gap-1 text-xs font-semibold bg-amber-100 text-amber-700 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Pending
                    </span>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-500 mb-2">Booking Pending</p>
                <p class="text-4xl font-bold text-gray-900">{{ $pendingBookings ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Field Usage Chart -->
        <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Persentase Penggunaan Lapangan</h3>
                    <p class="text-sm text-gray-500 mt-1">Data penggunaan: {{ $periodLabel }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        
            @if (($totalBookings ?? 0) > 0)
                <div class="space-y-6">
                    @foreach ($fieldDetails as $field)
                        @php 
                            $percentage = $field['percentage']; 
                            
                            $colorMap = [
                                'red'    => ['bg' => 'bg-red-500',    'text' => 'text-red-600',    'light' => 'bg-red-50'],
                                'yellow' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'light' => 'bg-yellow-50'],
                                'green'  => ['bg' => 'bg-green-500',  'text' => 'text-green-600',  'light' => 'bg-green-50'],
                                'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'light' => 'bg-purple-50'],
                                'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'light' => 'bg-indigo-50'],
                                'pink'   => ['bg' => 'bg-pink-500',   'text' => 'text-pink-600',   'light' => 'bg-pink-50'],
                            ];

                            $bgClass    = $colorMap[$field['color']]['bg'] ?? 'bg-gray-500';
                            $textClass  = $colorMap[$field['color']]['text'] ?? 'text-gray-600';
                            $lightClass = $colorMap[$field['color']]['light'] ?? 'bg-gray-50';
                        @endphp

                        <div class="group hover:bg-gray-50 p-4 rounded-xl transition-all duration-200">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 {{ $bgClass }} rounded-full"></div>
                                    <span class="text-base font-semibold text-gray-800">{{ $field['name'] }}</span>
                                </div>
                                <span class="text-lg font-bold {{ $textClass }} px-3 py-1 {{ $lightClass }} rounded-lg">{{ $percentage }}%</span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full {{ $bgClass }} transition-all duration-700 ease-out shadow-sm"
                                    style="width: {{ $percentage }}%;"></div>
                            </div>

                            <p class="text-xs text-gray-500 mt-2 ml-6">{{ $field['bookings'] }} total booking accepted</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-600">Tidak ada data penggunaan lapangan</p>
                    <p class="text-sm text-gray-400 mt-1">Periode {{ $periodLabel }}</p>
                </div>
            @endif
        </div>

        <!-- Faculty Usage -->
        <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Fakultas</h3>
                    <p class="text-xs text-gray-500 mt-1">Penggunaan per fakultas</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse ($facultyUsage as $faculty)
                    <div class="flex justify-between items-center p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 group">
                        <div class="flex items-center gap-3 flex-1">
                           
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $faculty['name'] }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold rounded-xl bg-blue-50 text-blue-700 group-hover:bg-blue-100 transition-colors">
                            {{ $faculty['bookings'] }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 text-center">Data penggunaan fakultas<br>belum tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush