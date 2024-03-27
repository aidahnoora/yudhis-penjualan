<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Penjualan</title>
    <style>
        /* CSS styling untuk PDF */
        /* Misalnya: */
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
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Laporan Transaksi Penjualan</h1>
    <table>
        <thead>
            <tr>
                <th>Nomor Nota</th>
                <th>Total</th>
                <!-- Tambahkan kolom lain sesuai kebutuhan -->
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksiPenjualan as $transaksi)
                <tr>
                    <td>{{ $transaksi->nota }}</td>
                    <td>{{ $transaksi->total }}</td>
                    <!-- Isi kolom lain sesuai kebutuhan -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
