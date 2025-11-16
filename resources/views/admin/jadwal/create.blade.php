@extends('layouts.app')
@section('page_title', 'Admin / Tambah Jadwal Lapangan')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tambah Jadwal Lapangan
    </h2>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-md mx-auto">
    <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium text-gray-700">Lapangan</label>
            <input type="text" 
                   name="lapangan" 
                   value="{{ old('lapangan', $lapangan) }}" 
                   readonly
                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
        </div>

        <div>
            <label class="block font-medium text-gray-700">Tanggal</label>
            <input type="date" 
                   name="tanggal" 
                   value="{{ old('tanggal', $jadwal->tanggal ?? '') }}"
                   {{ isset($jadwal->tanggal) ? 'readonly disabled' : '' }}
                   class="mt-1 block w-full rounded-md border-gray-300 
                          {{ isset($jadwal->tanggal) ? 'bg-gray-100 cursor-not-allowed' : '' }}">
        </div>

        <div>
            <label class="block font-medium text-gray-700">Jam Mulai</label>

            @php
                $startHour = 7;
                $endHour = 22;

                $selectedJamMulai = old('jam_mulai', $jadwal->jam_mulai ?? null);
            @endphp

            <select name="jam_mulai"
                {{ isset($jadwal->jam_mulai) ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300
                       {{ isset($jadwal->jam_mulai) ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                
                @for ($hour = $startHour; $hour <= $endHour; $hour++)
                    <option value="{{ sprintf('%02d:00', $hour) }}"
                        {{ $selectedJamMulai == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                        {{ sprintf('%02d:00', $hour) }}
                    </option>
                @endfor
            </select>
        </div>

        @php
            $startHourForSelesai = $selectedJamMulai ? (int)substr($selectedJamMulai, 0, 2) : 7;
            $defaultJamSelesaiHour = $startHourForSelesai + 2;
            if ($defaultJamSelesaiHour > 24) $defaultJamSelesaiHour = 24;

            $defaultJamSelesai = sprintf('%02d:00', $defaultJamSelesaiHour);
            $minJamSelesai = $startHourForSelesai + 1;

            $selectedJamSelesai = old('jam_selesai', $jadwal->jam_selesai ?? $defaultJamSelesai);
        @endphp

        <div>
            <label class="block font-medium text-gray-700">Jam Selesai</label>

            <select name="jam_selesai"
                {{ isset($jadwal->jam_selesai) ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300
                       {{ isset($jadwal->jam_selesai) ? 'bg-gray-100 cursor-not-allowed' : '' }}">

                @for ($i = $minJamSelesai; $i <= 24; $i++)
                    <option value="{{ sprintf('%02d:00', $i) }}"
                        {{ $selectedJamSelesai == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                        {{ sprintf('%02d:00', $i) }}
                    </option>
                @endfor
            </select>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Nama Pemesan</label>
            <input type="text" 
                   name="nama" 
                   value="{{ old('nama', auth()->user()->name ?? '') }}" 
                   readonly
                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
        </div>

        <div>
            <label class="block font-medium text-gray-700">Status</label>
            <select name="status"
                {{ isset($jadwal->status) ? 'disabled' : '' }}
                class="mt-1 block w-full rounded-md border-gray-300
                       {{ isset($jadwal->status) ? 'bg-gray-100 cursor-not-allowed' : '' }}">

                <option value="pending" 
                    {{ old('status', $jadwal->status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="approved" 
                    {{ old('status', $jadwal->status ?? '') == 'approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="rejected" 
                    {{ old('status', $jadwal->status ?? '') == 'rejected' ? 'selected' : '' }}>
                    Rejected
                </option>
            </select>
        </div>

        <div>
            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Jadwal
            </button>
        </div>

    </form>
</div>
@endsection
