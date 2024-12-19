<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Cabang;



class UserController extends Controller
{
    //

    public function create(Request $request)
    {
        $cabang_id = $request->cabang_id;
        $cabangs = Cabang::all(); // Ambil semua cabang
        return view('superadmin/tambahUsers', compact('cabang_id', 'cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required|integer',
            'cabang_id' => 'required|integer|exists:cabangs,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type,
            'cabang_id' => $request->cabang_id,
        ]);

        return redirect()->route('dataCabang')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $cabangs = Cabang::all(); // Ambil semua cabang
        return view('superadmin.editUsers', compact('user', 'cabangs'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->type = $request->input('type');
        $user->cabang_id = $request->input('cabang_id');
        $user->save();

        return redirect()->route('dataCabang')->with('success', 'Pengguna berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dataCabang')->with('success', 'Pengguna berhasil dihapus');
    }
    
}
