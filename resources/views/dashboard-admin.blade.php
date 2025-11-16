@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Dashboard Admin
</h2>
@endsection

@section('content')
<main class="flex-1 p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Analisis Penggunaan Lapangan Bulanan</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-blue-500 text-white shadow-lg p-5 rounded-xl transition duration-300 transform hover:scale-[1.02]">
            <p class="text-sm opacity-80">Total Booking Bulan Ini</p>
            <p class="text-4xl font-bold mt-1">{{ $totalBookings ?? 0 }}</p>
            <p class="text-xs mt-2 opacity-90">Total pemakaian lapangan</p>
        </div>

        <div class="bg-white shadow-lg p-5 rounded-xl border-l-4 border-red-500 transition duration-300 transform hover:scale-[1.02]">
            <p class="text-sm text-gray-500">Paling Banyak Digunakan</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $mostUsedField ?? 'N/A' }}</p> 
            <p class="text-xs mt-2 text-gray-500">Bulan {{ date('F Y') }}</p>
        </div>
        
        <div class="bg-white shadow-lg p-5 rounded-xl border-l-4 border-yellow-500 transition duration-300 transform hover:scale-[1.02]">
            <p class="text-sm text-gray-500">Booking Pending</p>
            <p class="text-4xl font-bold text-gray-900 mt-1">{{ $pendingBookings ?? 0 }}</p> 
            <p class="text-xs mt-2 text-gray-500">Perlu konfirmasi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Persentase Penggunaan Lapangan</h3>
            <div class="flex justify-center">
                <canvas id="donutChart" height="260"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                Penggunaan Berdasarkan Fakultas
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
                    <li class="py-3 text-gray-500">Data penggunaan fakultas bulan ini belum tersedia.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-10 bg-white shadow-lg rounded-xl p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Persentase Penggunaan Lapangan</h3>
        
        @if (($totalBookings ?? 0) > 0)
            <div class="space-y-6">
                @foreach ($fieldDetails as $field)
                    @php $percentage = $field['percentage']; @endphp

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-lg font-medium text-gray-700">{{ $field['name'] }}</span>
                            <span class="text-lg font-bold text-{{ $field['color'] }}-600">{{ $percentage }}%</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-{{ $field['color'] }}-500 transition-all duration-700"
                                style="width: {{ $percentage }}%;"></div>
                        </div>

                        <p class="text-xs text-gray-500 mt-1">{{ $field['bookings'] }} total booking accepted</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada data pemesanan yang disetujui bulan ini.</p>
        @endif
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const ctx = document.getElementById('donutChart').getContext('2d');

    const labels = @json(array_column($fieldDetails, 'name'));
    const data = @json(array_column($fieldDetails, 'percentage'));

    const colors = [
        '#ef4444', 
        '#facc15', 
        '#22c55e', 
        '#a855f7', 
        '#6366f1', 
        '#ec4899'  
    ];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: "#fff",
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: "60%",
            plugins: {
                legend: { position: "bottom" }
            }
        }
    });
});
</script>

@endsection
