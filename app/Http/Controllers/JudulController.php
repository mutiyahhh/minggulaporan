<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Judul;
use App\Models\SubJudul;



class JudulController extends Controller
{
    // Existing methods...

    public function tambahJudul()
    {
        return view('bagianlaporan.tambahjudulLaporan');
    }

    public function judullaporan()
    {
        $judul_laporan = Judul::paginate(10);
        return view('bagianlaporan.judulLaporan', compact('judul_laporan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_laporan' => 'required|string',
        ]);

        $judul = new Judul();
        $judul->judul_laporan = $validatedData['judul_laporan'];
        $judul->deskripsi_laporan = $validatedData['deskripsi_laporan'];
        $judul->save();

        // Redirect to the tambahsubjudulLaporan page with the new judul_laporan_id
        return redirect()->route('subjudul.laporan', ['id' => $judul->id])->with('success', 'Judul laporan berhasil ditambahkan!');
    }


    public function edit($id)
    {
        // Ambil data berdasarkan ID judul
        $judul = Judul::findOrFail($id);

        // Tampilkan form edit dengan data yang ada
        return view('bagianlaporan.editjudulLaporan', compact('judul'));
    }



    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_laporan' => 'required|string',
        ]);

        // Ambil data judul berdasarkan ID dan update
        $judul = Judul::findOrFail($id);
        $judul->judul_laporan = $request->input('judul_laporan');
        $judul->deskripsi_laporan = $request->input('deskripsi_laporan');
        $judul->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('judullaporan')->with('success', 'Judul laporan berhasil diupdate.');
    }




    public function showSubjudul($id)
    {
        $judul_laporan = Judul::findOrFail($id);
        $subjudul_laporan = SubJudul::where('judul_laporan_id', $id)->paginate(10);

        return view('bagianlaporan.subjudulLaporan', compact('judul_laporan', 'subjudul_laporan'));
    }

    // Show form to create a new subjudul_laporan
    public function createSubjudul($id)
    {
        $judul_laporan = Judul::findOrFail($id);

        return view('bagianlaporan.tambahsubjudulLaporan', compact('judul_laporan'));
    }

    // Store a new subjudul_laporan
    public function storeSubjudul(Request $request, $id)
    {
        $request->validate([
            'subjudul_laporan' => 'required|string|max:255',
            'tipe_laporan' => 'required|in:foto,video,text,file_lainya', // Validasi untuk tipe_laporan
            'deskripsi' => 'nullable|string|max:255',
            'is_wajib' => 'required|boolean', // Menambahkan validasi untuk is_wajib
        ]);

        SubJudul::create([
            'judul_laporan_id' => $id,
            'subjudul_laporan' => $request->subjudul_laporan,
            'tipe_laporan' => $request->tipe_laporan, // Simpan tipe_laporan
            'deskripsi' => $request->deskripsi,
            'is_wajib' => $request->is_wajib, // Simpan apakah wajib atau tidak
        ]);

        return redirect()->route('subjudul.laporan', ['id' => $id])->with('success', 'Subjudul Laporan created successfully.');
    }


    // Edit Subjudul
    public function editSubjudul($id)
    {
        $subjudul = SubJudul::findOrFail($id);
        return view('bagianlaporan.editsubjudulLaporan', compact('subjudul'));
    }

    // Update Subjudul
    public function updateSubjudul(Request $request, $id)
{
    $validatedData = $request->validate([
        'subjudul_laporan' => 'required|string|max:255',
        'tipe_laporan' => 'required|in:foto,video,text,file_lainya',
        'deskripsi' => 'nullable|string|max:255',
        'is_wajib' => 'required|boolean', // Validasi untuk is_wajib
    ]);

    $subjudul = SubJudul::findOrFail($id);
    $subjudul->subjudul_laporan = $validatedData['subjudul_laporan'];
    $subjudul->tipe_laporan = $validatedData['tipe_laporan'];
    $subjudul->deskripsi = $validatedData['deskripsi'];
    $subjudul->is_wajib = $validatedData['is_wajib']; // Update nilai is_wajib
    $subjudul->save();

    return redirect()->route('subjudul.laporan', ['id' => $subjudul->judul_laporan_id])->with('success', 'Subjudul laporan berhasil diperbarui!');
}
    // public function updateSubjudul(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'subjudul_laporan' => 'required|string|max:255',
    //         'tipe_laporan' => 'required|in:foto,video,text,file_lainya',
    //         'deskripsi' => 'nullable|string|max:255',
    //     ]);

    //     $subjudul = SubJudul::findOrFail($id);
    //     $subjudul->subjudul_laporan = $validatedData['subjudul_laporan'];
    //     $subjudul->tipe_laporan = $validatedData['tipe_laporan'];
    //     $subjudul->deskripsi = $validatedData['deskripsi'];
    //     $subjudul->save();

    //     return redirect()->route('subjudul.laporan', ['id' => $subjudul->judul_laporan_id])->with('success', 'Subjudul laporan berhasil diperbarui!');
    // }
    

    public function destroy($id)
    {
        // Temukan judul laporan berdasarkan ID
        $judul = Judul::findOrFail($id);

        // Hapus judul laporan
        $judul->delete();

        // Redirect kembali ke halaman daftar judul laporan dengan pesan sukses
        return redirect()->route('judullaporan')->with('success', 'Judul laporan berhasil dihapus!');
    }


    // Destroy Subjudul
    public function destroySubjudul($id)
    {
        $subjudul = SubJudul::findOrFail($id);
        $subjudul->delete();

        return redirect()->route('subjudul.laporan', ['id' => $subjudul->judul_laporan_id])->with('success', 'Subjudul laporan berhasil dihapus!');
    }
    
}
