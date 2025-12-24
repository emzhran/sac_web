@extends('layouts.app')

@section('page_title', 'Dashboard Admin')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen">
    
    {{-- Header & Filter Section --}}
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-6 md:mb-8 gap-4 md:gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">
                Dashboard Admin
            </h1>
            <p class="text-xs md:text-sm text-gray-500">Laporan Periode: <span class="font-semibold text-indigo-600">{{ $periodLabel }}</span></p>
        </div>

        {{-- FORM FILTER RESPONSIVE --}}
        <form action="{{ url()->current() }}" method="GET" class="w-full xl:w-auto flex flex-col sm:flex-row gap-3">
            
            {{-- Container Input Group (Menyatu) --}}
            {{-- Mobile: flex-col + divide-y | Desktop: flex-row + divide-x --}}
            <div class="flex flex-col md:flex-row w-full bg-white border border-gray-200 rounded-xl shadow-sm divide-y md:divide-y-0 md:divide-x divide-gray-100 overflow-hidden">
                
                {{-- Select Hari --}}
                <div class="relative group w-full md:w-auto">
                    <select name="day" class="appearance-none bg-none bg-transparent border-none py-3 md:py-2.5 pl-4 pr-10 text-sm text-gray-700 font-medium focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors outline-none w-full md:min-w-[100px]">
                        <option value="">Harian</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Select Bulan --}}
                <div class="relative group w-full md:w-auto">
                    <select name="month" class="appearance-none bg-none bg-transparent border-none py-3 md:py-2.5 pl-4 pr-10 text-sm text-gray-700 font-medium focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors outline-none w-full md:min-w-[120px]">
                        @foreach($months as $key => $label)
                            <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Select Tahun --}}
                <div class="relative group w-full md:w-auto">
                    <select name="year" class="appearance-none bg-none bg-transparent border-none py-3 md:py-2.5 pl-4 pr-10 text-sm text-gray-700 font-medium focus:ring-0 cursor-pointer hover:bg-gray-50 transition-colors outline-none w-full md:min-w-[100px]">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Tombol Search --}}
                {{-- Mobile: Full width button --}}
                <button type="submit" class="w-full md:w-auto px-4 py-3 md:py-2.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200 font-medium text-sm flex items-center justify-center gap-2">
                    <span class="md:hidden">Terapkan Filter</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            {{-- Tombol Reset --}}
            <a href="{{ url()->current() }}" class="flex items-center justify-center p-3 md:p-2.5 bg-white border border-gray-200 md:border-transparent md:bg-transparent rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200 shadow-sm md:shadow-none" title="Reset Filter">
                <span class="md:hidden text-sm font-medium mr-2 text-gray-500">Reset</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </a>
        </form>
    </div>

    {{-- Stats Grid (Sudah Responsive) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
        
        {{-- Card 1: Total Booking --}}
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/30 p-5 md:p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <span class="text-[10px] md:text-xs font-semibold bg-white/20 px-2 md:px-3 py-1 rounded-full text-right truncate max-w-[100px] md:max-w-[120px]">{{ $periodLabel }}</span>
                </div>
                <p class="text-xs md:text-sm font-medium opacity-90 mb-1">Total Booking</p>
                <p class="text-3xl md:text-4xl font-bold">{{ $totalBookings ?? 0 }}</p>
            </div>
        </div>

        {{-- Card 2: Paling Banyak --}}
        <div class="bg-white shadow-lg p-5 md:p-6 rounded-2xl border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-rose-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs md:text-sm font-medium text-gray-500 mb-2">Paling Banyak Digunakan</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900 mb-1 truncate" title="{{ $mostUsedField }}">{{ $mostUsedField ?? 'N/A' }}</p>
            </div>
        </div>
        
        {{-- Card 3: Pending --}}
        <div class="bg-white shadow-lg p-5 md:p-6 rounded-2xl border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-50 to-transparent rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if(($pendingBookings ?? 0) > 0)
                    <span class="flex items-center gap-1 text-[10px] md:text-xs font-semibold bg-amber-100 text-amber-700 px-2 md:px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Pending
                    </span>
                    @endif
                </div>
                <p class="text-xs md:text-sm font-medium text-gray-500 mb-2">Booking Pending</p>
                <p class="text-3xl md:text-4xl font-bold text-gray-900">{{ $pendingBookings ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Detail & Fakultas Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Detail Progress Bar --}}
        <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl p-5 md:p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Persentase Penggunaan</h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Data periode: {{ $periodLabel }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        
            @if (($totalBookings ?? 0) > 0)
                <div class="space-y-4 md:space-y-6">
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

                        <div class="group hover:bg-gray-50 p-3 md:p-4 rounded-xl transition-all duration-200">
                            <div class="flex flex-wrap justify-between items-center mb-2 gap-2">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div class="w-2.5 h-2.5 md:w-3 md:h-3 {{ $bgClass }} rounded-full flex-shrink-0"></div>
                                    <span class="text-sm md:text-base font-semibold text-gray-800 break-words">{{ $field['name'] }}</span>
                                </div>
                                <span class="text-sm md:text-lg font-bold {{ $textClass }} px-2 md:px-3 py-0.5 md:py-1 {{ $lightClass }} rounded-lg">{{ $percentage }}%</span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2 md:h-3 overflow-hidden">
                                <div class="h-2 md:h-3 rounded-full {{ $bgClass }} transition-all duration-700 ease-out shadow-sm"
                                    style="width: {{ $percentage }}%;"></div>
                            </div>

                            <p class="text-[10px] md:text-xs text-gray-500 mt-1 md:mt-2 ml-4 md:ml-6">{{ $field['bookings'] }} total booking</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 md:h-64 text-gray-400">
                    <p class="font-medium text-sm md:text-base text-gray-600 text-center">Belum ada data</p>
                </div>
            @endif
        </div>

        {{-- Fakultas List --}}
        <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl p-5 md:p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <div>
                    <h3 class="text-base md:text-lg font-bold text-gray-900">Fakultas</h3>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-1">Total per fakultas</p>
                </div>
            </div>
            
            <div class="space-y-2 md:space-y-3 max-h-80 md:max-h-96 overflow-y-auto pr-1">
                @forelse ($facultyUsage as $faculty)
                    <div class="flex justify-between items-center p-2.5 md:p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 group">
                        <div class="flex items-center gap-3 flex-1 overflow-hidden">
                            <span class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-gray-900 truncate">{{ $faculty['name'] }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs md:text-sm font-bold rounded-xl bg-blue-50 text-blue-700 group-hover:bg-blue-100 transition-colors">
                            {{ $faculty['bookings'] }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-32 md:h-48 text-gray-400">
                        <p class="text-xs md:text-sm text-gray-500 text-center">Data kosong</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection