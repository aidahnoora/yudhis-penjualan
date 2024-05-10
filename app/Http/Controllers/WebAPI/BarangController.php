<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarangResource;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('supplier')->latest()->paginate(100);

        return new BarangResource(true, 'List Data Barangs', $barangs);
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::latest()->paginate(100);

        return new BarangResource(true, 'List Supplier', $suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $barang = Barang::create([
            'nama' => $request->nama,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'image' => $image->hashName(),
        ]);

        return new BarangResource(true, 'Data Barang Berhasil Ditambahkan!', $barang);
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        return new BarangResource(true, 'Detail Data Barang!', $barang);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'stok' => 'required',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/' . basename($barang->image));

            $barang->update([
                'nama' => $request->nama,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'image' => $image->hashName(),
            ]);
        } else {
            $barang->update([
                'nama' => $request->nama,
                'stok' => $request->stok,
                'harga' => $request->harga,
            ]);
        }

        return new BarangResource(true, 'Data Barang Berhasil Diubah!', $barang);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        $barang->delete();

        return new BarangResource(true, 'Data Barang Berhasil Dihapus!', null);
    }
}
