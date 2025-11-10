@extends('layouts.app')

@section('page_title', 'Admin / CRUD Lapangan')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manajemen CRUD Lapangan Fisik
    </h2>
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Daftar Lapangan Terdaftar</h3>

            <a href="{{ route('admin.lapangan.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md transition shadow-md">
                + Tambah Lapangan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($lapangans->isEmpty())
            <div class="p-4 text-center text-gray-500 bg-gray-100 rounded-lg">
                Belum ada Lapangan Fisik yang terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lapangan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($lapangans as $lapangan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lapangan->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">
                                    {{ $lapangan->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.lapangan.edit', $lapangan) }}"
                                       class="text-indigo-600 hover:text-indigo-900 mx-1 p-1 rounded-md hover:bg-indigo-50" title="Edit">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 mx-1 p-1 rounded-md hover:bg-red-50" title="Hapus" onclick="confirmDelete(this.form, '{{ $lapangan->nama }}')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(form, lapanganNama) {
            Swal.fire({
                title: `Hapus Lapangan`,
                html: `Apakah Anda yakin ingin menghapus lapangan <b>${lapanganNama}</b>?<br><br>Semua booking terkait juga akan terhapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush