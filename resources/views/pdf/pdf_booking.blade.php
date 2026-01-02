<!DOCTYPE html>
<html>

<head>
    <title>PDF Booking #{{ $booking->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            padding-bottom: 60px;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #2d2893ff;
            padding-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .text-cell {
            vertical-align: middle;
            text-align: left;
            padding-left: 15px;
        }

        .header h1 {
            margin: 0;
            color: #2c278aff;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 12px 0;
            vertical-align: middle;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            width: 180px;
            color: #555;
        }

        .val {
            color: #111;
            font-weight: 500;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .schedule-table th {
            background-color: #f3f4f6;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #999;
            padding: 10px 0;
            background-color: #fff;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Logo UMY" width="80" height="auto" style="display: block;">
                </td>
                <td class="text-cell">
                    <h1>BUKTI PEMINJAMAN LAPANGAN</h1>
                    <p>Universitas Muhammadiyah Yogyakarta.</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pemesan</td>
            <td class="val">{{ $booking->user->name ?? 'Nama Tidak Ditemukan' }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="val">{{ $booking->user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Lapangan</td>
            <td class="val">{{ $booking->lapangan->nama ?? 'Lapangan' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="val" style="text-transform: uppercase; font-weight: bold; color: green;">
                {{ $booking->status == 'approved' ? 'Disetujui' : $booking->status }}
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td class="val">{{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <h3>Jadwal Peminjaman Lapangan</h3>
    <table class="schedule-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->jadwals as $jadwal)
            <tr>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $jadwal->jam_mulai }}</td>
                <td>{{ $jadwal->jam_selesai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Harap tunjukkan bukti ini kepada petugas.</p>
        <p>&copy; Sistem Booking Lapangan UMY{{ date('Y') }}</p>
    </div>

</body>

</html>