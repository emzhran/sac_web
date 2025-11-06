@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Kelola Lapangan: Futsal (Permintaan {{ ucfirst(request('status', 'Pending')) }})
</h2>
@endsection

@section('content')
<main class="flex-1 p-6">
    <div class="bg-white shadow-xl rounded-xl p-8">
        <h3 class="text-2xl font-extrabold text-gray-900 mb-6 border-b pb-3">Daftar Permintaan Futsal</h3>

        @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline font-medium">{{ session('status') }}</span>
        </div>
        @endif
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline font-medium">Terjadi kesalahan: {{ $errors->first() }}</span>
        </div>
        @endif

        @isset($dbError)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline font-bold">GAGAL MENGAMBIL DATA: {{ $dbError }}</span>
        </div>
        @endisset

        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-3">Filter Status:</h4>
            <div class="space-x-4 flex flex-wrap">
                @php
                $currentStatus = request('status', 'pending');
                $statuses = ['pending', 'approved', 'rejected', 'all'];
                @endphp

                @foreach ($statuses as $status)
                @php
                $label = ucfirst($status);
                $isActive = $currentStatus === $status;
                $class = $isActive
                ? 'bg-indigo-600 text-white shadow-lg scale-105'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
                @endphp
                <a href="{{ route('admin.lapangan.futsal', ['status' => $status]) }}"
                    class="inline-flex items-center px-6 py-2 border-0 rounded-full text-sm font-bold transition-all duration-300 transform mb-2 {{ $class }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-3/12">Nama Pemesan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-2/12">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-2/12">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-2/12">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse ($futsalBookings as $index => $booking)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">{{ $booking->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($booking->jadwal)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $status = $booking->status;
                            $statusClass = [
                            'approved' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if ($booking->status === 'pending')

                            <form id="approved-form-{{ $booking->id }}"
                                action="{{ route('admin.lapangan.update_status', ['type' => 'futsals', 'id' => $booking->id, 'status' => 'approved']) }}"
                                method="POST"
                                class="inline-block sa-action-form">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                    class="text-green-600 hover:text-white font-bold px-3 py-1 transition-all duration-200 hover:bg-green-500 rounded-lg shadow-sm sa-approve-btn"
                                    data-booking-id="{{ $booking->id }}"
                                    data-action="approved">
                                    Approve
                                </button>
                            </form>

                            <form id="rejected-form-{{ $booking->id }}"
                                action="{{ route('admin.lapangan.update_status', ['type' => 'futsals', 'id' => $booking->id, 'status' => 'rejected']) }}"
                                method="POST"
                                class="inline-block ml-2 sa-action-form">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                    class="text-red-600 hover:text-white font-bold px-3 py-1 transition-all duration-200 hover:bg-red-500 rounded-lg shadow-sm sa-reject-btn"
                                    data-booking-id="{{ $booking->id }}"
                                    data-action="rejected">
                                    Reject
                                </button>
                            </form>

                            @else
                            <span class="text-gray-400 font-medium italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-base text-gray-500 bg-gray-50">
                            <span class="font-bold">Status</span> "{{ ucfirst($currentStatus) }}" tidak ada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const approveButtons = document.querySelectorAll('.sa-approve-btn');
        const rejectButtons = document.querySelectorAll('.sa-reject-btn');

        function handleAction(event) {
            const button = event.target;
            const action = button.dataset.action;
            const bookingId = button.dataset.bookingId;
            const form = document.getElementById(`${action}-form-${bookingId}`);

            let title = '';
            let text = '';
            let icon = '';
            let confirmButtonColor = '';

            if (action === 'approved') {
                title = 'Verifikasi Pemesanan?';
                text = 'Anda yakin ingin MENYETUJUI pemesanan ini?';
                icon = 'question';
                confirmButtonColor = '#10B981';
            } else if (action === 'rejected') {
                title = 'Tolak Pemesanan?';
                text = 'Anda yakin ingin MENOLAK pemesanan ini?';
                icon = 'warning';
                confirmButtonColor = '#EF4444';
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: action === 'approved' ? 'Ya, Setujui!' : 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    container: 'font-sans',
                    popup: 'rounded-xl',
                    confirmButton: 'shadow-md',
                    cancelButton: 'shadow-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form tidak ditemukan untuk aksi:', action, 'dan ID:', bookingId);
                    }
                }
            });
        }

        approveButtons.forEach(button => {
            button.addEventListener('click', handleAction);
        });

        rejectButtons.forEach(button => {
            button.addEventListener('click', handleAction);
        });
    });
</script>
@endsection