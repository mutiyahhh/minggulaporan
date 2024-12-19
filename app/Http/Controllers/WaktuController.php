<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\WaktuBulan;
use App\Models\WaktuTahun;


class WaktuController extends Controller
{
    // Tahun //

    public function waktuTahunan()
    {
        $waktu_tahun_laporan = WaktuTahun::paginate(10);
        return view('waktu.waktuTahun', compact('waktu_tahun_laporan'));
    }

    public function tambahTahun()
    {
        return view('waktu.tambahwaktuTahun');
    }

    public function store(Request $request)
    {
        $request->validate([
            'waktu_tahun_laporan' => 'required|integer|min:2019|max:' . (date('Y') + 1),
            'catatan' => 'nullable|string',
        ]);

        WaktuTahun::create([
            'waktu_tahun_laporan' => $request->waktu_tahun_laporan, // Tidak perlu Carbon karena sudah integer
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('waktuTahunan')->with('success', 'Waktu Tahunan berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $waktu_tahun = WaktuTahun::findOrFail($id);
        return view('waktu.editwaktuTahun', compact('waktu_tahun'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'waktu_tahun_laporan' => 'required|integer|min:2019|max:' . (date('Y') + 1),
            'catatan' => 'nullable|string',
        ]);

        $waktu_tahun = WaktuTahun::findOrFail($id);

        $waktu_tahun->update([
            'waktu_tahun_laporan' => $request->waktu_tahun_laporan, // Sama seperti store, langsung gunakan request
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('waktuTahunan')->with('success', 'Waktu Tahunan berhasil diupdate.');
    }

    // susilo code:
    //public function destroy($id)
    // {
    //     $waktu_tahun = WaktuTahun::findOrFail($id);
    //     $waktu_tahun->delete();

    //     return redirect()->route('waktuTahunan')->with('success', 'Waktu Tahunan berhasil dihapus.');
    // }
    public function destroy($id)
{
    $waktu_tahun = WaktuTahun::findOrFail($id);

    // Log before delete
    \Log::info('Deleting WaktuTahun: ', $waktu_tahun->toArray());

    $waktu_tahun->delete();

    // Check if deleted
    $exists = WaktuTahun::find($id);
    if ($exists === null) {
        \Log::info('WaktuTahun deleted successfully.');
    } else {
        \Log::error('Failed to delete WaktuTahun: ', $exists->toArray());
    }

    return redirect()->route('waktuTahunan')->with('success', 'Waktu Tahunan berhasil dihapus.');
}


    // Bulan //

    public function waktuBulanan($tahunId)
    {
        $waktu_tahun = WaktuTahun::findOrFail($tahunId);
        $waktu_bulan_laporan = WaktuBulan::where('waktu_tahun_laporan_id', $tahunId)->paginate(10);
        return view('waktu.waktuBulan', compact('waktu_bulan_laporan', 'waktu_tahun'));
    }

    public function tambahBulan($tahunId)
    {
        $waktu_tahun = WaktuTahun::findOrFail($tahunId);
        return view('waktu.tambahwaktuBulan', compact('waktu_tahun'));
    }

    public function storeBulan(Request $request, $tahunId)
    {
        $request->validate([
            'waktu_bulan_laporan' => 'required|date_format:Y-m',
            'start' => 'required|date_format:Y-m-d\TH:i',
            'end' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convert YYYY-MM to YYYY-MM-01
        $waktu_bulan_laporan = $request->waktu_bulan_laporan . '-01';

        WaktuBulan::create([
            'waktu_tahun_laporan_id' => $tahunId,
            'waktu_bulan_laporan' => $waktu_bulan_laporan,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect()->route('waktuBulanan', $tahunId)->with('success', 'Waktu Bulanan berhasil ditambahkan.');
    }

    public function editBulan($tahunId, $bulanId)
    {
        $waktu_bulan = WaktuBulan::findOrFail($bulanId);
        $waktu_tahun = WaktuTahun::findOrFail($tahunId);
        return view('waktu.editwaktuBulan', compact('waktu_bulan', 'waktu_tahun'));
    }

    public function updateBulan(Request $request, $tahunId, $bulanId)
    {
        $request->validate([
            'waktu_bulan_laporan' => 'required|date_format:Y-m',
            'start' => 'required|date_format:Y-m-d\TH:i',
            'end' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convert YYYY-MM to YYYY-MM-01
        $waktu_bulan_laporan = $request->waktu_bulan_laporan . '-01';

        $waktu_bulan = WaktuBulan::findOrFail($bulanId);
        $waktu_bulan->update([
            'waktu_bulan_laporan' => $waktu_bulan_laporan,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect()->route('waktuBulanan', $tahunId)->with('success', 'Waktu Bulanan berhasil diupdate.');
    }

    public function destroyBulan($tahunId, $bulanId)
    {
        $waktu_bulan = WaktuBulan::findOrFail($bulanId);
        $waktu_bulan->delete();

        return redirect()->route('waktuBulanan', $tahunId)->with('success', 'Waktu Bulanan berhasil dihapus.');
    }




}
