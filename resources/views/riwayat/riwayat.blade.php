@extends('layouts.app')

@section('page_title', 'Riwayat Peminjaman')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Riwayat Peminjaman Anda
</h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Daftar Riwayat Peminjaman</h2>

    @if ($riwayats->isEmpty())
        <p class="text-gray-500">Belum ada riwayat peminjaman.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-left">Lapangan</th>
                        <th class="px-4 py-2 border text-left">Tanggal</th>
                        <th class="px-4 py-2 border text-left">Jam Mulai</th>
                        <th class="px-4 py-2 border text-left">Jam Selesai</th>
                        <th class="px-4 py-2 border text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayats as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border font-semibold text-blue-600">
                                {{ $r->lapangan->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $r->jadwal->tanggal 
                                    ? \Carbon\Carbon::parse($r->jadwal->tanggal)->format('d M Y') 
                                    : '-' }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $r->jadwal->jam_mulai ?? '-' }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $r->jadwal->jam_selesai ?? '-' }}
                            </td>
                            <td class="px-4 py-2 border">
                                @if ($r->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Pending</span>
                                @elseif ($r->status === 'approved')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Disetujui</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $riwayats->links() }}
        </div>
    @endif
</div>
@endsection
