<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Penjualan</title>
    <style>
        /* Style sesuai kebutuhan Anda */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @php
        $getBulan = request()->input('bulan');
        $tahun = request()->input('tahun');
        $total = 0;

        $namaBulanIndonesia = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $bulan = $namaBulanIndonesia[$getBulan - 1];
    @endphp

    <h3 style="text-align: center">Laporan Transaksi Penjualan - Bulan {{ $bulan }} Tahun {{ $tahun }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nota</th>
                <th>Customer</th>
                <th>Tanggal Transaksi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $transaksi)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td style="text-align: center">{{ $transaksi->nota }}</td>
                    <td>{{ $transaksi->customer->nama }}</td>
                    <td style="text-align: center">{{ $transaksi->tgl_transaksi }}</td>
                    <td style="text-align: right">Rp. {{ number_format($transaksi->total) }}</td>

                    @php $total += $transaksi->total; @endphp
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total Transaksi</td>
                <td style="text-align: right">Rp. {{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
