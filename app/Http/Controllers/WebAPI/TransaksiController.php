<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarangResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\ItemTransaksiPenjualan;
use App\Models\TransaksiPenjualan;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPenjualan::with('customer')
            ->with('user')
            ->latest()->paginate(100);

        return new TransaksiResource(true, 'List Data Transaksi', $transaksis);
    }

    public function getCustomers()
    {
        $customers = Customer::latest()->paginate(100);

        return new CustomerResource(true, 'List Customer', $customers);
    }

    public function getBarangs()
    {
        $barangs = Barang::latest()->paginate(100);

        return new BarangResource(true, 'List Barang', $barangs);
    }

    public function getItemTransaksis($transaksiId)
    {
        $itemTransaksis = ItemTransaksiPenjualan::with('barang')->where('penjualan_id', $transaksiId)
            ->latest()->paginate(100);

        return new TransaksiResource(true, 'List Item Transaksi', $itemTransaksis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'nota' => 'required',
            'tgl_transaksi' => 'required',
            'items' => 'required|array',
            'items.*.barang_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = DB::transaction(function () use ($request) {
            $header = TransaksiPenjualan::create([
                'user_id' => 1,
                'customer_id' => $request->customer_id,
                'nota' => $request->nota,
                'tgl_transaksi' => $request->tgl_transaksi,
                'total' => $request->total,
            ]);

            $items = [];
            foreach ($request->items as $item) {
                // $subtotal = $item['qty'] * $item['harga'];
                $items[] = [
                    'penjualan_id' => $header->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ItemTransaksiPenjualan::insert($items);

            // Update otomatis stok data barang
            foreach ($request->items as $item) {
                Barang::where('id', $item['barang_id'])->decrement('stok', $item['qty']);
            }

            return $header;
        });

        return new TransaksiResource(true, 'Data Transaksi Berhasil Ditambahkan!', $transaksi);
    }

    public function show($id)
    {
        $transaksi = TransaksiPenjualan::find($id);

        return new TransaksiResource(true, 'Detail Data Transaksi!', $transaksi);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'nota' => 'required',
            'tgl_transaksi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = TransaksiPenjualan::find($id);

        $transaksi->update([
            'user_id' => 1,
            'customer_id' => $request->customer_id,
            'nota' => $request->nota,
            'tgl_transaksi' => $request->tgl_transaksi,
            'total' => $request->total,
        ]);

        return new TransaksiResource(true, 'Data Transaksi Berhasil Diubah!', $transaksi);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPenjualan::find($id);

        $transaksi->delete();

        return new TransaksiResource(true, 'Data Transaksi Berhasil Dihapus!', null);
    }

    public function report(Request $request)
    {
        $bulanTahun = $request->query('bulan_tahun');
        $tahun = $request->query('tahun');

        $transaksiPenjualan = TransaksiPenjualan::with('customer')
            ->whereMonth('tgl_transaksi', date('m', strtotime($bulanTahun)))
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        return response()->json(['data' => $transaksiPenjualan]);
    }
}
