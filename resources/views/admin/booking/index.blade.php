@extends('layouts.app')

@section('page_title', 'Admin / Kelola Booking')

@section('content')
<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Kelola Booking
            </h1>
            <p class="text-sm text-gray-500">
                Pantau dan kelola persetujuan peminjaman lapangan.
            </p>
        </div>
    </div>

    <div class="mb-6 flex flex-wrap gap-3">
        @php
            $currentStatus = $status ?? 'pending';
            $tabs = [
                [
                    'key' => 'pending', 
                    'label' => 'Menunggu', 
                    'active' => 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-500/30',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                [
                    'key' => 'approved', 
                    'label' => 'Disetujui', 
                    'active' => 'bg-emerald-500 text-white border-emerald-500 shadow-lg shadow-emerald-500/30',
                    'icon' => 'M5 13l4 4L19 7'
                ],
                [
                    'key' => 'rejected', 
                    'label' => 'Ditolak', 
                    'active' => 'bg-rose-500 text-white border-rose-500 shadow-lg shadow-rose-500/30',
                    'icon' => 'M6 18L18 6M6 6l12 12'
                ],
                [
                    'key' => 'all', 
                    'label' => 'Semua', 
                    'active' => 'bg-gray-600 text-white border-gray-600 shadow-lg shadow-gray-500/30',
                    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'
                ],
            ];
        @endphp

        @foreach($tabs as $tab)
            <a href="{{ route('admin.booking.index', ['status' => $tab['key']]) }}" 
               class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border flex items-center gap-2
               {{ $currentStatus == $tab['key'] 
                    ? $tab['active'] 
                    : "bg-white text-gray-600 border-gray-200 hover:bg-gray-50" }}">
                
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"></path>
                </svg>
                
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <div class="bg-white shadow-xl shadow-indigo-500/5 rounded-2xl border border-gray-100 overflow-hidden">
        
        @if (session('status'))
            <div class="bg-emerald-50 text-emerald-700 p-4 text-sm font-medium border-b border-emerald-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada data booking dengan status ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lapangan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pemesan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal Main</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            @if ($currentStatus === 'pending')
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($bookings as $booking)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                                            @if(Str::contains($booking->lapangan->nama ?? '', 'Futsal')) ⚽ 
                                            @elseif(Str::contains($booking->lapangan->nama ?? '', 'Basket')) 🏀 
                                            @elseif(Str::contains($booking->lapangan->nama ?? '', 'Voli')) 🏐 
                                            @else 🏸 @endif
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $booking->lapangan->nama ?? 'Unknown' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->user->name ?? 'User Tidak Dikenal' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $booking->user->email ?? '' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($booking->jadwals->isNotEmpty())
                                        @foreach($booking->jadwals as $jadwal)
                                            <div class="mb-1 last:mb-0">
                                                <div class="text-sm font-semibold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-red-400 italic">Data jadwal hilang</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusStyles = match($booking->status) {
                                            'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20',
                                            'pending' => 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20',
                                            'rejected' => 'bg-rose-100 text-rose-700 ring-1 ring-rose-600/20',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                @if ($currentStatus === 'pending')
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <form id="approve-form-{{ $booking->id }}" method="POST" action="{{ route('admin.booking.update_status', ['booking' => $booking->id, 'status' => 'approved']) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" 
                                                    onclick="confirmAction('approve-form-{{ $booking->id }}', '{{ $booking->lapangan->nama ?? 'Lapangan' }}', 'setujui')"
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center" 
                                                    title="Setujui">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>

                                            <form id="reject-form-{{ $booking->id }}" method="POST" action="{{ route('admin.booking.update_status', ['booking' => $booking->id, 'status' => 'rejected']) }}">
                                                @csrf @method('PATCH')
                                                <button type="button" 
                                                    onclick="confirmAction('reject-form-{{ $booking->id }}', '{{ $booking->lapangan->nama ?? 'Lapangan' }}', 'tolak')"
                                                    class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center" 
                                                    title="Tolak">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(formId, lapanganNama, actionType) {
        const isApprove = actionType === 'setujui';
        
        Swal.fire({
            title: `Konfirmasi ${isApprove ? 'Persetujuan' : 'Penolakan'}`,
            html: `Apakah Anda yakin ingin <b>${actionType}</b> pemesanan untuk <br><b>${lapanganNama}</b>?`,
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#10b981' : '#f43f5e',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush