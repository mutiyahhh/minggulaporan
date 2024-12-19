<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\Cabang;
use App\Models\User;


class AdminController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::all();
        
        return view('superadmin.dataCabang', compact('cabangs'));
    }

    public function cari(Request $request)
{
    $search = $request->input('search');

    // Query to search and paginate results
    $cabangs = Cabang::when($search, function ($query, $search) {
        return $query->where('nama_cabang', 'like', "%{$search}%")
                     ->orWhere('area', 'like', "%{$search}%")
                     ->orWhere('alamat', 'like', "%{$search}%");
    })->paginate(10);

    $userType = auth()->user()->type;

    return view('superadmin.dataCabang', compact('cabangs', 'userType', 'search'));
}

    public function dataCabang()
    {
        $cabangs = Cabang::paginate(10);
        return view('superadmin.dataCabang', compact('cabangs'));
    }

    public function tambahCabang()
    {
        $cabangs = Cabang::all();  // Mengambil semua data cabang
        return view('superadmin.tambahCabang', compact('cabangs'));
    }

    public function show($id)
    {
        $cabang = Cabang::findOrFail($id);  // Mengambil cabang berdasarkan id
        $users = $cabang->users;  // Mengambil semua user yang terkait dengan cabang

        // Mengirim data ke view
        return view('superadmin.detailCabang', compact('cabang', 'users'));
    }

    public function destroy($id)
    {
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        return redirect()->route('cabangs.index')->with('success', 'Cabang berhasil dihapus');
    }

    public function destroycabang($id)
{
    $cabang = Cabang::find($id);
    $cabang->delete();

    return redirect()->route('cabangs.index')->with('success', 'Cabang berhasil dihapus');
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'area' => $request->area,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
        ]);

        return redirect()->route('dataCabang')->with('success', 'Cabang baru berhasil ditambahkan.');
    }

    public function edit($id)
{
    $cabang = Cabang::findOrFail($id);
    return view('superadmin.editCabang', compact('cabang'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_cabang' => 'required|string|max:255',
        'area' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'nomor_hp' => 'required|string|max:255',
    ]);

    $cabang = Cabang::findOrFail($id);
    $cabang->update([
        'nama_cabang' => $request->nama_cabang,
        'area' => $request->area,
        'alamat' => $request->alamat,
        'nomor_hp' => $request->nomor_hp,
    ]);

    return redirect()->route('dataCabang')->with('success', 'Cabang berhasil diperbarui.');
}
}