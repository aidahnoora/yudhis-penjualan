<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(100);

        return new SupplierResource(true, 'List Data Suppliers', $suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $supplier = Supplier::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return new SupplierResource(true, 'Data Supplier Berhasil Ditambahkan!', $supplier);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);

        return new SupplierResource(true, 'Detail Data Supplier!', $supplier);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $supplier = Supplier::find($id);

        $supplier->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return new SupplierResource(true, 'Data Supplier Berhasil Diubah!', $supplier);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        $supplier->delete();

        return new SupplierResource(true, 'Data Supplier Berhasil Dihapus!', null);
    }
}
