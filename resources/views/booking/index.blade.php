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
        <p class="text-sm text-gray-500">Lihat jadwal 7 hari ke depan dan pilih slot waktu untuk bermain.</p>
    </div>

    <div class="bg-white shadow-lg shadow-indigo-500/5 rounded-2xl border border-gray-100 p-6">
        
        {{-- BAGIAN 1: LOGIKA PERSIAPAN DATA & FILTER --}}
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 mb-3 block">Pilih Lapangan:</label>
            <div class="flex flex-wrap gap-3">
                @php
                    // 1. Ambil semua data lapangan untuk tombol filter
                    $allLapangansData = \App\Models\Lapangan::all();

                    // Ambil nama unik (kata pertama) untuk tombol tab
                    $uniqueNames = $allLapangansData->map(fn($lap) => explode(' ', trim($lap->nama))[0]) 
                        ->unique()
                        ->values();

                    // Menentukan filter yang sedang aktif
                    $currentFilterBase = explode(' ', $lapanganFilterName)[0];

                    // 2. LOGIKA PENCARIAN ID (Dipindah ke atas agar efisien)
                    $foundLapangan = \App\Models\Lapangan::where('nama', $lapanganFilterName)
                        ->orWhere('nama', 'LIKE', $lapanganFilterName . '%') 
                        ->first();

                    if (!$foundLapangan) {
                        $foundLapangan = \App\Models\Lapangan::first();
                    }

                    $globalLapanganId = $foundLapangan ? $foundLapangan->id : null;
                @endphp

                {{-- Loop Tombol Filter --}}
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

        {{-- BAGIAN 2: TABEL JADWAL --}}
        <div class="overflow-hidden rounded-xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 sticky left-0 bg-gray-50 z-10">
                                Waktu
                            </th>
                            @foreach ($dates as $date)
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider min-w-[140px]
                                           {{ $date->isToday() ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">
                                    <div class="flex flex-col">
                                        <span>{{ $date->translatedFormat('D') }}</span>
                                        <span class="text-xs font-normal opacity-80">{{ $date->translatedFormat('d M') }}</span>
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
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100 bg-gray-50/30 sticky left-0 z-10">
                                    {{ $jamMulai }} - {{ $jamSelesai }}
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

                                    <td class="px-2 py-2 text-center align-middle border-l border-dashed border-gray-100">
                                        @if ($isBooked)
                                            {{-- TAMPILAN JIKA SUDAH DIBOOKING --}}
                                            @php
                                                $status = strtolower($isBooked['status']);
                                                $styles = match ($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                                    'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                                    default => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                                };
                                            @endphp
                                            <div class="flex flex-col items-center justify-center p-2 rounded-lg {{ $styles }}">
                                                <span class="text-xs font-bold truncate max-w-[120px]">{{ $isBooked['nama'] }}</span>
                                                <span class="text-[10px] uppercase tracking-wide opacity-80 font-semibold">{{ ucfirst($status) }}</span>
                                            </div>

                                        @elseif ($isPast)
                                            {{-- TAMPILAN WAKTU LAMPAU --}}
                                            <div class="h-full w-full py-2 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center">
                                                 <span class="text-gray-400 text-xs italic">Lewat</span>
                                            </div>

                                        @else
                                            {{-- TAMPILAN SLOT KOSONG --}}
                                            @if($globalLapanganId)
                                                <a href="{{ route('booking.create', [
                                                            'lapangan' => $globalLapanganId, 
                                                            'tanggal' => $tanggal, 
                                                            'jam_mulai' => $jamMulai,
                                                            'jam_selesai' => $jamSelesai
                                                        ]) }}"
                                                   class="group flex items-center justify-center w-full h-full py-2 rounded-lg border border-transparent 
                                                          hover:bg-indigo-50 hover:border-indigo-200 transition-all cursor-pointer">
                                                    <span class="text-gray-300 text-xs font-medium italic group-hover:text-indigo-600 group-hover:not-italic group-hover:font-bold">
                                                        + Book
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-rose-400 text-xs font-medium bg-rose-50 px-2 py-1 rounded">Error</span>
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

        {{-- FOOTER NOTE --}}
        <div class="mt-6 flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm leading-relaxed">
                <strong>Catatan Penting:</strong> 
                Slot dengan tanda <span class="text-indigo-600 font-bold">+ Book</span> tersedia untuk dipesan.
                Jadwal yang ditampilkan mencakup <strong>7 hari ke depan</strong>. Mohon pastikan tanggal dan jam sudah sesuai sebelum melakukan pembayaran.
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN 3: MODAL VERIFIKASI EMAIL --}}
@if(session('email_unverified'))
<div id="emailModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md text-center shadow-2xl transform transition-all scale-100">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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