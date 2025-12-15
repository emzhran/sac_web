@extends('layouts.app')
@section('page_title', 'Booking Lapangan')

@section('header')
@endsection

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">
            Jadwal & Ketersediaan
        </h1>
        <p class="text-sm text-gray-500">Lihat jadwal 7 hari ke depan.</p>
    </div>

    <div class="bg-white shadow-lg shadow-indigo-500/5 rounded-2xl border border-gray-100 p-6">
        
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 mb-3 block">Pilih Lapangan:</label>
            <div class="flex flex-wrap gap-3">
                @php
                    $allLapangansData = \App\Models\Lapangan::all();

                    $uniqueNames = $allLapangansData->map(fn($lap) => explode(' ', trim($lap->nama))[0]) 
                        ->unique()
                        ->sort()
                        ->values();

                    $currentFilterBase = explode(' ', $lapanganFilterName)[0];

                    $foundLapangan = \App\Models\Lapangan::where('nama', $lapanganFilterName)
                        ->orWhere('nama', 'LIKE', $lapanganFilterName . '%') 
                        ->first();

                    if (!$foundLapangan) {
                        $foundLapangan = \App\Models\Lapangan::first();
                    }

                    $globalLapanganId = $foundLapangan ? $foundLapangan->id : null;
                @endphp

                @foreach ($uniqueNames as $lapName)
                    <a href="{{ route('booking.index', ['lapangan' => $lapName]) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border
                              {{ $currentFilterBase == $lapName 
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30' 
                                    : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $lapName }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200">
            
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-28 px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 bg-gray-50">
                                Waktu
                            </th>
                            @foreach ($dates as $date)
                                <th scope="col"
                                    class="px-1 py-4 text-center text-xs font-bold uppercase tracking-wider
                                           {{ $date->isToday() ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">
                                    <div class="flex flex-col">
                                        <span>{{ strtoupper($date->translatedFormat('D')) }}</span>
                                        <span class="text-[10px] font-normal opacity-80 mt-1">{{ $date->translatedFormat('d M') }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($timeSlots as $jamMulai)
                            @php
                                $jamSelesai = sprintf('%02d:00', (int)substr($jamMulai, 0, 2) + 1);
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                
                                <td class="px-2 py-3 text-center whitespace-nowrap text-xs font-bold text-gray-900 border-r border-gray-200 bg-gray-50">
                                    {{ $jamMulai }}
                                    <div class="text-[10px] text-gray-400 font-normal mt-0.5">{{ $jamSelesai }}</div>
                                </td>

                                @foreach ($dates as $date)
                                    @php
                                        $tanggal = $date->toDateString();
                                        $isBooked = null;

                                        if (isset($allBookings[$lapanganFilterName][$tanggal])) {
                                            foreach ($allBookings[$lapanganFilterName][$tanggal] as $booking) {
                                                if ($jamMulai >= $booking['jam_mulai'] && $jamMulai < $booking['jam_selesai']) {
                                                    $isBooked = $booking;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        $isPast = $date->isBefore(now()->startOfDay());
                                    @endphp

                                    <td class="px-1 py-1 h-14 text-center align-middle border-l border-dashed border-gray-100">
                                        
                                        @if ($isBooked)
                                            @php
                                                $status = strtolower($isBooked['status']);
                                                $styles = match ($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                                    'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                                    default => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                                };
                                            @endphp
                                            <div class="w-full h-full min-h-[40px] flex flex-col items-center justify-center rounded-md {{ $styles }} p-1 cursor-default">
                                                <span class="text-[10px] font-bold leading-tight truncate w-full px-1">
                                                    {{ \Illuminate\Support\Str::limit($isBooked['nama'], 12, '...') }}
                                                </span>
                                            </div>

                                        @elseif ($isPast)
                                            <div class="w-full h-full min-h-[40px] bg-gray-50 rounded-md flex items-center justify-center">
                                                 <span class="text-gray-300 text-[10px]">-</span>
                                            </div>

                                        @else
                                            @if($globalLapanganId)
                                                <a href="{{ route('booking.create', [
                                                            'lapangan' => $globalLapanganId, 
                                                            'tanggal' => $tanggal, 
                                                            'jam_mulai' => $jamMulai,
                                                            'jam_selesai' => $jamSelesai
                                                        ]) }}"
                                                   class="group flex items-center justify-center w-full h-full min-h-[40px] rounded-md border border-transparent 
                                                          hover:bg-indigo-50 hover:border-indigo-200 transition-all cursor-pointer">
                                                    <span class="text-gray-300 text-[10px] group-hover:text-indigo-600 group-hover:font-bold">
                                                        + booking
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-rose-400 text-[10px]">x</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        <div class="text-sm leading-relaxed">
            <div class="mb-3">
                <strong>Catatan:</strong> Klik <span class="text-indigo-600 font-bold">+ booking</span> untuk melakukan peminjaman lapangan. Pastikan tanggal dan jam sudah sesuai.
            </div>

                <div class="flex flex-wrap items-center gap-3 text-xs border-t border-amber-200/50 pt-3 mt-1">
                    <span class="text-amber-800 font-semibold mr-1">Status:</span>
                    
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-amber-200 text-amber-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-emerald-200 text-emerald-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Disetujui
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-rose-200 text-rose-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Ditolak
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('email_unverified'))
<div id="emailModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md text-center shadow-2xl transform transition-all scale-100">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Email Diperlukan</h3>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Untuk keamanan, harap verifikasi alamat email Anda terlebih dahulu sebelum melakukan booking lapangan.
        </p>
        
        <div class="flex flex-col gap-3">
            <button id="resendEmailBtn" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30">
                Kirim Ulang Email Verifikasi
            </button>
            <button onclick="document.getElementById('emailModal').remove();" 
                class="w-full px-4 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                Tutup / Saya sudah verifikasi
            </button>
        </div>

        <div id="resendMessage" class="mt-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium hidden border border-green-100"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('resendEmailBtn').addEventListener('click', function() {
        const btn = this;
        const msgDiv = document.getElementById('resendMessage');
        const originalText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Mengirim...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        axios.post('{{ route('verification.send') }}', {}, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            msgDiv.textContent = 'Tautan verifikasi telah dikirim ke email Anda!';
            msgDiv.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-100');
            msgDiv.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
            btn.textContent = 'Terkirim';
        })
        .catch(error => {
            msgDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
            msgDiv.classList.remove('hidden', 'bg-green-50', 'text-green-700', 'border-green-100');
            msgDiv.classList.add('bg-red-50', 'text-red-700', 'border-red-100');
            btn.textContent = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        });
    });
</script>
@endif

@endsection