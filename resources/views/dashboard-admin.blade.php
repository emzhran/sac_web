@extends('layouts.app')

@section('page_title', 'Dashboard Admin')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-12">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard Penggunaan Lapangan <span class="hidden md:inline">- {{ $monthName }}</span>
        </h1>

        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-100">
            <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Bulan:</label>
            
            <select 
                id="month" 
                name="month" 
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2 pl-3 pr-8 cursor-pointer"
                onchange="this.form.submit()" 
            >
                @foreach($availableMonths as $value => $label)
                    <option value="{{ $value }}" {{ $filterDate == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <a href="{{ url()->current() }}" class="text-xs text-gray-500 underline hover:text-gray-800 ml-1 whitespace-nowrap">
                Reset
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-blue-500 text-white shadow-lg p-5 rounded-xl">
            <p class="text-sm opacity-80">Total Booking {{ $monthName }}</p>
            <p class="text-4xl font-bold mt-1">{{ $totalBookings ?? 0 }}</p>
            <p class="text-xs mt-2 opacity-90">Total pemakaian lapangan disetujui</p>
        </div>

        <div class="bg-white shadow-lg p-5 rounded-xl border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Paling Banyak Digunakan</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $mostUsedField ?? 'N/A' }}</p> 
            <p class="text-xs mt-2 opacity-90">Berdasarkan Pengguanan terbanyak</p>
        </div>
        
        <div class="bg-white shadow-lg p-5 rounded-xl border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Booking Pending</p>
            <p class="text-4xl font-bold text-gray-900 mt-1">{{ $pendingBookings ?? 0 }}</p> 
            <p class="text-xs mt-2 text-gray-500">Menunggu konfirmasi bulan ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Persentase Penggunaan Lapangan</h3>
        
            @if (($totalBookings ?? 0) > 0)
                <div class="space-y-6">
                    @foreach ($fieldDetails as $field)
        @php 
            $percentage = $field['percentage']; 
            
            $colorMap = [
                'red'    => ['bg' => 'bg-red-500',    'text' => 'text-red-600'],
                'yellow' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600'],
                'green'  => ['bg' => 'bg-green-500',  'text' => 'text-green-600'],
                'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600'],
                'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600'],
                'pink'   => ['bg' => 'bg-pink-500',   'text' => 'text-pink-600'],
            ];

            $bgClass   = $colorMap[$field['color']]['bg'] ?? 'bg-gray-500';
            $textClass = $colorMap[$field['color']]['text'] ?? 'text-gray-600';
        @endphp

        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <span class="text-lg font-medium text-gray-700">{{ $field['name'] }}</span>
                <span class="text-lg font-bold {{ $textClass }}">{{ $percentage }}%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full {{ $bgClass }} transition-all duration-700"
                    style="width: {{ $percentage }}%;"></div>
            </div>

            <p class="text-xs text-gray-500 mt-1">{{ $field['bookings'] }} total booking accepted</p>
        </div>
    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p>Belum ada data pemesanan yang disetujui pada bulan {{ $monthName }}.</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                Penggunaan Fakultas
            </h3>
            
            <ul class="divide-y divide-gray-200">
                @forelse ($facultyUsage as $faculty)
                    <li class="flex justify-between items-center py-3">
                        <span class="text-gray-700">{{ $faculty['name'] }}</span>
                        <span class="px-3 py-1 text-sm font-bold rounded-full bg-blue-100 text-blue-800">
                            {{ $faculty['bookings'] }} Booking
                        </span>
                    </li>
                @empty
                    <li class="py-3 text-gray-500 italic text-sm text-center">Data penggunaan fakultas bulan ini belum tersedia.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush