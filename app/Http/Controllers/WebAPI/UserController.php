<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(100);

        return new UserResource(true, 'List Data Users', $users);
    }

    public function show($id)
    {
        $user = User::find($id);

        return new UserResource(true, 'Detail Data User!', $user);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return new UserResource(true, 'Data User Berhasil Diubah!', $user);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        return new UserResource(true, 'Data User Berhasil Dihapus!', null);
    }
}
