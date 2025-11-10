@extends('layouts.app')

@section('page_title', 'Admin / Kelola Booking')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Kelola Permintaan Booking Lapangan
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">Daftar Permintaan {{ ucfirst($status ?? 'pending') }}</h3>

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif
    
    <div class="mb-4 flex space-x-2">
        <a href="{{ route('admin.booking.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ ($status ?? 'pending') == 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Pending
        </a>
        <a href="{{ route('admin.booking.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ ($status ?? 'pending') == 'approved' ? 'bg-green-500 text-white border-green-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.booking.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ ($status ?? 'pending') == 'rejected' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Ditolak
        </a>
        <a href="{{ route('admin.booking.index', ['status' => 'all']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ ($status ?? 'pending') == 'all' ? 'bg-gray-700 text-white border-gray-700' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Semua
        </a>
    </div>

    @if ($bookings->isEmpty())
        <div class="p-4 text-center text-gray-500 bg-gray-100 rounded-lg">
            Tidak ada permintaan booking dengan status {{ ucfirst($status ?? 'pending') }}.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lapangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        @if (($status ?? 'pending') === 'pending')
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">
                                {{ $booking->lapangan->nama ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->nama_pemesan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                               @if($booking->jadwal)
                                    {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                                    ({{ $booking->jadwal->jam_mulai }} - {{ $booking->jadwal->jam_selesai }})
                                @else
                                    Jadwal tidak ditemukan
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            @if (($status ?? 'pending') === 'pending')
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <form id="approve-form-{{ $booking->id }}" method="POST" 
                                          action="{{ route('admin.booking.update_status', ['booking' => $booking->id, 'status' => 'approved']) }}" 
                                          class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                onclick="confirmAction('approve-form-{{ $booking->id }}', '{{ $booking->lapangan->nama ?? 'Lapangan' }}', 'setujui')"
                                                class="text-green-600 hover:text-green-900 mx-1 p-1 rounded-md hover:bg-green-50" title="Setujui">
                                            Setujui
                                        </button>
                                    </form>

                                    <form id="reject-form-{{ $booking->id }}" method="POST" 
                                          action="{{ route('admin.booking.update_status', ['booking' => $booking->id, 'status' => 'rejected']) }}" 
                                          class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                onclick="confirmAction('reject-form-{{ $booking->id }}', '{{ $booking->lapangan->nama ?? 'Lapangan' }}', 'tolak')"
                                                class="text-red-600 hover:text-red-900 mx-1 p-1 rounded-md hover:bg-red-50" title="Tolak">
                                            Tolak
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(formId, lapanganNama, actionType) {
        const isApprove = actionType === 'setujui';
        
        Swal.fire({
            title: `Konfirmasi ${actionType.toUpperCase()}`,
            html: `Apakah Anda yakin ingin ${actionType} pemesanan untuk lapangan <b>${lapanganNama}</b>? <br><br> Aksi ini tidak dapat dibatalkan.`,
            icon: isApprove ? 'warning' : 'error',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#3085d6' : '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isApprove ? `Ya, ${actionType}!` : `Ya, ${actionType}!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush