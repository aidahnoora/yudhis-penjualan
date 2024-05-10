<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarangResource;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\ItemTransaksiPembelian;
use App\Models\Supplier;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPembelian::with('supplier')
            ->latest()->paginate(100);

        return new TransaksiResource(true, 'List Data Transaksi', $transaksis);
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::latest()->paginate(100);

        return new SupplierResource(true, 'List Supplier', $suppliers);
    }

    public function getBarangs()
    {
        $barangs = Barang::latest()->paginate(100);

        return new BarangResource(true, 'List Barang', $barangs);
    }

    public function getItemTransaksis($transaksiId)
    {
        $itemTransaksis = ItemTransaksiPembelian::with('barang')->where('pembelian_id', $transaksiId)
            ->latest()->paginate(100);

        return new TransaksiResource(true, 'List Item Transaksi', $itemTransaksis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
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
            $header = TransaksiPembelian::create([
                'supplier_id' => $request->supplier_id,
                'nota' => $request->nota,
                'tgl_transaksi' => $request->tgl_transaksi,
                'total' => $request->total,
            ]);

            $items = [];
            foreach ($request->items as $item) {
                // $subtotal = $item['qty'] * $item['harga'];
                $items[] = [
                    'pembelian_id' => $header->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ItemTransaksiPembelian::insert($items);

            // Update otomatis stok data barang
            foreach ($request->items as $item) {
                Barang::where('id', $item['barang_id'])->increment('stok', $item['qty']);
            }

            return $header;
        });

        return new TransaksiResource(true, 'Data Transaksi Berhasil Ditambahkan!', $transaksi);
    }

    public function show($id)
    {
        $transaksi = TransaksiPembelian::find($id);

        return new TransaksiResource(true, 'Detail Data Transaksi!', $transaksi);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'tgl_transaksi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaksi = TransaksiPembelian::find($id);

        $transaksi->update([
            'supplier_id' => $request->supplier_id,
            'tgl_transaksi' => $request->tgl_transaksi,
            'total' => $request->total,
        ]);

        return new TransaksiResource(true, 'Data Transaksi Berhasil Diubah!', $transaksi);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPembelian::find($id);

        $transaksi->delete();

        return new TransaksiResource(true, 'Data Transaksi Berhasil Dihapus!', null);
    }

    public function filter(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $transaksis = TransaksiPembelian::with('supplier')->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        return response()->json(['transaksis' => $transaksis]);
    }

    public function exportPDF(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $transaksis = TransaksiPembelian::with('supplier')->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        $namaBulan = \DateTime::createFromFormat('!m', $bulan)->format('F'); // Ubah angka bulan menjadi nama bulan
        $now = now();
        $namaFile = '[' . $now . ']' . ' - Laporan-Transaksi-Pembelian-' . $namaBulan . '-' . $tahun . '.pdf';

        $pdf = \PDF::loadView('pdf.transaksi-pembelian', ['transaksis' => $transaksis]);

        return $pdf->download($namaFile);
    }
}
