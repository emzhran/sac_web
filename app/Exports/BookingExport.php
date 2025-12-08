<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return Booking::query()
            ->with(['user', 'lapangan', 'jadwals'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00', 
                $this->endDate . ' 23:59:59'
            ])
            ->orderBy('created_at', 'desc');
    }

    public function map($booking): array
    {
        $jadwalString = $booking->jadwals->map(function($j) {
            return \Carbon\Carbon::parse($j->tanggal)->format('d M Y') . 
                   ' (' . $j->jam_mulai . '-' . $j->jam_selesai . ')';
        })->implode(", \n");

        return [
            $booking->id,
            $booking->created_at->format('d M Y H:i'),
            $booking->user->name ?? 'User Terhapus',
            $booking->user->email ?? '-',
            $booking->user->nim ?? '-',
            $booking->lapangan->nama ?? 'Lapangan Terhapus',
            $jadwalString,
            ucfirst($booking->status),
        ];
    }

    public function headings(): array
    {
        return [
            'ID Booking',
            'Tanggal Pengajuan',
            'Nama Pemesan',
            'Email',
            'NIM',
            'Lapangan',
            'Detail Jadwal Main',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}