<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
  public function index()
  {
    $customers = Customer::latest()->paginate(100);

    return new CustomerResource(true, 'List Data Customers', $customers);
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

    $customer = Customer::create([
      'nama' => $request->nama,
      'no_hp' => $request->no_hp,
      'alamat' => $request->alamat,
    ]);

    return new CustomerResource(true, 'Data Customer Berhasil Ditambahkan!', $customer);
  }

  public function show($id)
  {
    $customer = Customer::find($id);

    return new CustomerResource(true, 'Detail Data Customer!', $customer);
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

    $customer = Customer::find($id);

    $customer->update([
      'nama' => $request->nama,
      'no_hp' => $request->no_hp,
      'alamat' => $request->alamat,
    ]);

    return new CustomerResource(true, 'Data Customer Berhasil Diubah!', $customer);
  }

  public function destroy($id)
  {
    $customer = Customer::find($id);

    $customer->delete();

    return new CustomerResource(true, 'Data Customer Berhasil Dihapus!', null);
  }
}
