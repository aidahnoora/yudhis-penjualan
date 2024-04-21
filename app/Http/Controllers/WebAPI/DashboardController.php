<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getTransaction()
    {
        $jumlahTransaksiPerBulan = TransaksiPenjualan::select(
            DB::raw('
                CASE EXTRACT(MONTH FROM tgl_transaksi)
                WHEN 1 THEN \'Januari\'
                WHEN 2 THEN \'Februari\'
                WHEN 3 THEN \'Maret\'
                WHEN 4 THEN \'April\'
                WHEN 5 THEN \'Mei\'
                WHEN 6 THEN \'Juni\'
                WHEN 7 THEN \'Juli\'
                WHEN 8 THEN \'Agustus\'
                WHEN 9 THEN \'September\'
                WHEN 10 THEN \'Oktober\'
                WHEN 11 THEN \'November\'
                WHEN 12 THEN \'Desember\'
            END AS bulan
            '),
            DB::raw('COUNT(*) as jumlah_transaksi')
        )
            ->whereYear('tgl_transaksi', date('Y'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tgl_transaksi)'))
            ->get();

        // dd($jumlahTransaksiPerBulan);

        return new DashboardResource(true, 'List Jumlah Transaksi Penjualan', $jumlahTransaksiPerBulan);
    }
}
