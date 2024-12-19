<?php

namespace App\Http\Controllers;

use App\Models\BulanLaporan;
use App\Models\MingguanLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DetailLaporan;
use App\Models\Judul;
use App\Models\SubJudul;
use App\Models\WaktuBulan;
use App\Models\WaktuTahun;
use App\Models\DetailReportMonthly;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterLaporanController extends Controller
{
    private const masterMonth = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    private const mingguLaporan = [
        1 => 'Minggu 1',
        2 => 'Minggu 2',
        3 => 'Minggu 3',
        4 => 'Minggu 4',
    ];

    private static function masterMonth() {
        return self::masterMonth;
    }

    private static function masterWeek() {
        return self::mingguLaporan;
    }

    public function daftarlaporan()
    {
        $judul_laporan = Judul::paginate(10);
        return view('masterlaporan.daftarLaporan', compact('judul_laporan'));
    }

    public function showJudulLaporan(Request $request) {
        // 0 -> weekly
        // 1 -> monthly
        $is_monthly = $request->query('is_monthly');

        if ($is_monthly == 0) {
            // Ambil semua judul laporan dari database
            $judulLaporan = Judul::with(['details'])
                ->whereHas('details', function ($query)  {
                    $query->where('jenis_laporan', 'weekly');
                })
                ->get();
        } else {
            $judulLaporan = Judul::with(['details'])
                ->whereHas('details', function ($query)  {
                    $query->where('jenis_laporan', 'monthly');
                })
                ->get();
        }

        // Tampilkan view 'judulLaporan' dan kirimkan data judul laporan
        return view('masterlaporan.judulLaporan', compact('judulLaporan'));
    }

        public function showYearsByJudul($id) {
            // Ambil judul laporan berdasarkan ID
            $judul = Judul::findOrFail($id);

            // Ambil semua tahun unik dari detail_laporan berdasarkan judul_laporan_id
            $detailLaporan = DetailLaporan::with('waktuTahun')
                                ->where('judul_laporan_id', $id)
                                ->get()
                                ->groupBy(function($laporan) {
                                    return $laporan->waktuTahun->waktu_tahun_laporan; // Group by tahun
                                });

            // Kirim data judul dan tahun laporan ke view
            return view('masterlaporan.tahunLaporan', compact('detailLaporan', 'judul'));
        }


    public function bulanLaporan($year, $detail_id, $judul_id)
    {
        // Ambil data judul laporan berdasarkan judul_id
        $judul_laporan = Judul::find($judul_id); // Menggunakan model Judul

        // Cek apakah judul laporan ditemukan
        if (!$judul_laporan) {
            return redirect()->back()->with('error', 'Judul laporan tidak ditemukan.');
        }

        // Ambil semua detail laporan yang sesuai dengan tahun yang dipilih
        $detailLaporan = DetailLaporan::with('waktuTahun')
            ->where('id', $detail_id)->get();

        $detailLaporanGroupBy = DB::table('detail_laporan')
            ->selectRaw('detail_laporan.id, detail_laporan.start_time, detail_laporan.end_time')
            ->where('id', $detail_id)
            ->groupBy('detail_laporan.id', 'detail_laporan.start_time', 'detail_laporan.end_time')
            ->get();

        $monthAllowedInput = [];
        $day_time_now = Carbon::now();
        foreach ($this->masterMonth() as $indexMonth => $month) {
            foreach ($detailLaporanGroupBy as $detailLaporan) {
                $startDate = $detailLaporan->start_time;
                $endDate = $detailLaporan->end_time;
                $startMonth = Carbon::parse($startDate)->format('m');
                $endMonth = Carbon::parse($endDate)->format('m');
                $endDayMonth = Carbon::parse($endDate);
                if ($startMonth == $indexMonth) {
                    if ((int)$day_time_now->diffInDays($endDayMonth) > 0) {
                        $monthAllowedInput[] = [
                            'month' => $indexMonth,
                            'name_of_month' => $month,
                            'allowed' => true,
                        ];
                    } else {
                        $monthAllowedInput[] = [
                            'month' => $indexMonth,
                            'name_of_month' => $month,
                            'allowed' => false,
                        ];
                    }

                }
                if ($startMonth != $endMonth && $endMonth == $indexMonth) {
                     if ((int)$day_time_now->diffInDays($endDayMonth) > 0) {
                        $monthAllowedInput[] = [
                            'month' => $indexMonth,
                            'name_of_month' => $month,
                            'allowed' => true,
                        ];
                    } else {
                        $monthAllowedInput[] = [
                            'month' => $indexMonth,
                            'name_of_month' => $month,
                            'allowed' => false,
                        ];
                    }
                }
            }
        }

        $monthAllowedInputSimplify = [];
        foreach ($monthAllowedInput as $index => $object) {
            $monthAllowedInputSimplify[] = $object['month'];
        }

        $masterMonthAll = [];
        foreach ($this->masterMonth() as $indexMonth => $month) {
            $masterMonthAll[] = $indexMonth;
        }

        $monthNotAllowedList = array_diff($masterMonthAll, $monthAllowedInputSimplify);

        $notAllowedList = [];
        foreach ($monthNotAllowedList as $index) {
            foreach ($this->masterMonth() as $indexMonth => $month) {
                if ($index == $indexMonth) {
                    $notAllowedList[] = [
                      'month' => $indexMonth,
                      'name_of_month' => $month,
                      'allowed' => false,
                    ];
                }
            }
        }

        $listMonthInput = array_merge($notAllowedList, $monthAllowedInput);

        $listMonthInput = collect($listMonthInput)->sortBy('month')->toArray();

        // Kirim data ke view
        return view('masterlaporan.bulanLaporan', compact('year', 'listMonthInput', 'judul_laporan', 'detailLaporan'));
    }


    public function showAllMonthlyReport($year, $month, $judul_id) //bulanan
    {
        if (auth()->user()->type == "manager" || auth()->user()->type == "admin") {
            $laporans = BulanLaporan::with(['judul.details', 'user'])
                ->select(
                    'bulanan_laporan.created_by',
                    'bulanan_laporan.status'
                )
                ->where('judul_laporan_id', $judul_id)
                ->where('year', $year)
                ->where('month', $month)
                ->groupBy(
                    'bulanan_laporan.created_by',
                    'bulanan_laporan.status'
                )
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $laporans = BulanLaporan::with(['judul.details', 'user'])
                ->select(
                    'bulanan_laporan.created_by',
                    'bulanan_laporan.status'
                )
                ->where('judul_laporan_id', $judul_id)
                ->where('year', $year)
                ->where('month', $month)
                ->where('bulanan_laporan.created_by', auth()->user()->id)
                ->groupBy(
                    'bulanan_laporan.created_by',
                    'bulanan_laporan.status'
                )
                ->orderBy('id', 'desc')
                ->get();
        }

        $judulLaporan = Judul::findOrFail($judul_id);

        $monthName = self::getMonthly($month);

        return view('masterlaporan.showAllMonthlyReport', compact('judulLaporan', 'laporans', 'year', 'monthName', 'month'));
    }

    private static function getMonthly($month): string {
        switch ($month) {
            case 1:
                return 'Januari';
            case 2:
                return 'Februari';
            case 3:
                return 'Maret';
            case 4:
                return 'April';
            case 5:
                return 'Mei';
            case 6:
                return 'Juni';
            case 7:
                return 'Juli';
            case 8:
                return 'Agustus';
            case 9:
                return 'September';
            case 10:
                return 'Oktober';
            case 11:
                return 'November';
            case 12:
                return 'Desember';
        }
    }

    private static function getWeekly($week): string {
         switch ($week) {
            case 1:
                return 'Minggu 1';
            case 2:
                return 'Minggu 2';
            case 3:
                return 'Minggu 3';
            case 4:
                return 'Minggu 4';
         }
    }

    public function mingguLaporan($year, $judul_id, $detail_id, $bulan_id)
    {
        // Ambil data judul laporan
        $judulLaporan = Judul::findOrFail($judul_id);

        // Ambil data bulan laporan
        $nameBulan = $this->getMonthly($bulan_id);
        $bulanLaporan = [
            'id' => $bulan_id,
            'waktu_bulan_laporan' => $nameBulan,
        ];
        //  dd($judulLaporan);

        // Ambil detail laporan berdasarkan judul dan bulan
        $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_id)
                                        ->where('id', $detail_id)
                                        ->get();
        $weeklyAllowedRaw = explode(",", $detailLaporan[0]->order_of_the_week);

        $masterWeek = $this->masterWeek();
        $weeklyNumber = [];
        foreach ($masterWeek as $indexWeek => $nameWeek) {
            $weeklyNumber[] = $indexWeek;
        }

        // Mengonversi array menjadi integer dan menghapus duplikat minggu yang diizinkan
        $weeklyAllowed = array_unique(array_map('intval', $weeklyAllowedRaw));

        $weekNotAllowedRaw = array_diff($weeklyNumber, $weeklyAllowed);

        // Membuat list minggu yang tidak diizinkan
        $listWeeklyNotAllowed = [];
        foreach ($weekNotAllowedRaw as $indexWeek => $week) {
            $listWeeklyNotAllowed[] = [
                'week' => $week,
                'name_of_week' => $this->getWeekly($week),
                'allowed' => false,
            ];
        }

        // Membuat list minggu yang diizinkan
        $listWeeklyAllowed = [];
        foreach ($weeklyAllowed as $indexWeek => $week) {
            $listWeeklyAllowed[] = [
                'week' => $week,
                'name_of_week' => $this->getWeekly($week),
                'allowed' => true,
            ];
        }

        // Menggabungkan list minggu yang diizinkan dan tidak diizinkan
        $listWeeklyReporting = array_merge($listWeeklyAllowed, $listWeeklyNotAllowed);
        $mingguLaporan = collect($listWeeklyReporting)->sortBy('week')->toArray();

//        dd($detailLaporan);

        // Kirim data ke view
        return view('masterlaporan.mingguLaporan', compact('year', 'judulLaporan', 'bulanLaporan', 'listWeeklyReporting', 'detailLaporan'));
    }

    // public function bulanLaporan($tahun_id, $judul_id)
    // {
    //     // Ambil semua detail laporan yang sesuai dengan tahun yang dipilih
    //     $detailLaporan = DetailLaporan::with('waktuBulan')
    //         ->whereHas('waktuBulan', function($query) use ($tahun_id) {
    //             $query->where('waktu_tahun_laporan_id', $tahun_id);
    //         })->get();

    //     // Kelompokkan data berdasarkan bulan
    //     $bulanLaporan = $detailLaporan->groupBy(function($item) {
    //         return $item->waktuBulan->waktu_bulan_laporan; // Mengelompokkan berdasarkan bulan
    //     });

    //     // Ambil judul_id dari salah satu detail laporan (karena semua detail akan memiliki judul yang sama)
    //     $judul_id = $judul_id;
    //     // dd($bulanLaporan);
    //     // Kembalikan view untuk menampilkan bulan dengan judul_id yang sesuai
    //     return view('masterlaporan.bulanLaporan', compact('bulanLaporan', 'judul_id'));
    // }

        public function detailLaporan($id)
        {
            // Ambil judul laporan dengan relasi detailLaporan dan subjudul sekaligus
            $judulLaporan = Judul::with('details.subjudul')
                ->where('id', $id)
                ->first();

            // Cek jika judulLaporan tidak ditemukan
            if (!$judulLaporan) {
                return redirect()->back()->with('error', 'Data laporan tidak ditemukan.');
            }

            //  dd($judulLaporan);

            // Kirim data ke view
            return view('masterlaporan.detailLaporan', compact('judulLaporan'));
        }


//     public function detailLaporan($bulan_id)
//     {
//         // Ambil data detail laporan berdasarkan bulan_id
//         $detailLaporan = DetailLaporan::where('waktu_bulan_laporan_id', $bulan_id)->get();
//
//         return view('masterlaporan.detailLaporan', compact('detailLaporan'));
//     }

    // public function detailLaporan($id)
    // {
    //     // Ambil data berdasarkan judul laporan yang dipilih
    //     $judulLaporan = Judul::with('details')
    //         ->whereHas('details', function($query) use ($id) {
    //             $query->where('judul_laporan_id', $id);
    //         })->get();

    //     if (!$judulLaporan) {
    //         return redirect()->back()->with('error', 'Data laporan tidak ditemukan.');
    //     }
    //     // Load detail laporan dari model yang sesuai
    //     $detailLaporan = DetailLaporan::with('subjudul')
    //         ->whereHas('subjudul', function($query) use ($id) {
    //             $query->where('judul_laporan_id', $id);
    //         })->get();
    //     return view('masterlaporan.detailLaporan', compact('judulLaporan', 'detailLaporan'));
    // }


    // public function old detailLaporan($id)
    // {
    //     // Ambil data bulan laporan berdasarkan ID
    //     $bulanLaporan = WaktuBulan::findOrFail($id);

    //     // Ambil detail laporan berdasarkan bulan
    //     $laporan = DetailLaporan::where('waktu_bulan_laporan_id', $id)
    //         ->with(['subjudul', 'judul', 'waktuBulan']) // Pastikan relasi sudah di-load
    //         ->get();

    //     // Kirimkan data ke view
    //     return view('masterlaporan.detailLaporan', compact('laporan', 'bulanLaporan'));
    // }

    public function showMainLaporan($id)
    {
        $judul = Judul::find($id);

        if (!$judul) {
            return redirect()->back()->with('error', 'Judul tidak ditemukan.');
        }

        // Mengambil data tahun dari model WaktuTahun
        $waktuTahunLaporanOptions = WaktuTahun::all();

        // Mengambil data subjudul
        $subjudulLaporanOptions = SubJudul::where('judul_laporan_id', $id)->get();

        return view('masterlaporan.mainLaporan', compact('judul', 'waktuTahunLaporanOptions', 'subjudulLaporanOptions'));
    }
public function store(Request $request)
{
    $time_now = Carbon::now();

    // Ambil input dari user
    $new_start_date_time = $request->year_date.'-'.$time_now->format('m').'-'.$request->start_time;
    $new_end_date_time = $request->year_date.'-'.$time_now->format('m').'-'.$request->end_time;

    $newStartDateParse = Carbon::parse($new_start_date_time)->format('Y-m-d');
    $newEndDateParse = Carbon::parse($new_end_date_time)->format('Y-m-d');

    $request->request->add([
        'start_date' => $newStartDateParse,
        'end_date' => $newEndDateParse
    ]);

    $rules = [
        'start_date' => 'required',
        'end_date'   => 'required|after:start_date',
    ];

    $messages = [
        'date_after' => ":attribute must be date after Start Date."
    ];

    $this->validate($request, $rules, $messages);

    $request->validate([
        'jenis_laporan' => 'required',
        'status_laporan' => 'required'
    ]);

    if ($request->jenis_laporan == "weekly") {

        Log::info('store laporan with judul_id: {judul_id} is weekly', ['judul_id' => $request->judul_laporan_id]);

        // Menghitung minggu berdasarkan start_date dan end_date
        $startDate = Carbon::parse($newStartDateParse);
        $endDate = Carbon::parse($newEndDateParse);

        // Menyimpan urutan minggu berdasarkan tanggal
        $weeks = [];
        $totalWeeks = $startDate->diffInWeeks($endDate); // Hitung total minggu di rentang tersebut

        // Menghitung minggu 1, 2, 3, 4 berdasarkan total minggu
        for ($i = 0; $i <= 3; $i++) {
            if ($i <= $totalWeeks) {
                $weeks[] = $i + 1; // Menambahkan minggu ke dalam array (Minggu 1, Minggu 2, dst)
            }
        }

        // Menyimpan data laporan berdasarkan minggu yang sudah dihitung
        foreach ($request->subjudul_laporan_id as $subIndex => $subjudul_laporan_id) {
            DetailLaporan::create([
                'created_by' => auth()->user()->id,
                'judul_laporan_id' => $request->judul_laporan_id,
                'subjudul_laporan_id' => $subjudul_laporan_id,
                'waktu_tahun_laporan_id' => $request->waktu_id,
                'start_time' => $request->start_date,
                'end_time' => $request->end_date,
                'catatan_laporan' => $request->catatan_laporan[$subIndex] ?? null,
                'jenis_laporan' => 'weekly',
                'order_of_the_week' => implode(',', $weeks),  // Menyimpan minggu dalam format 1, 2, 3, 4
            ]);
        }

    } else {

        Log::info('store laporan with judul_id: {judul_id} is monthly', ['judul_id' => $request->judul_laporan_id]);

        foreach ($request->subjudul_laporan_id as $subIndex => $subjudul_laporan_id) {
            DetailLaporan::create([
                'created_by' => auth()->user()->id,
                'judul_laporan_id' => $request->judul_laporan_id,
                'subjudul_laporan_id' => $subjudul_laporan_id,
                'waktu_tahun_laporan_id' => $request->waktu_id,
                'start_time' => $request->start_date,
                'end_time' => $request->end_date,
                'catatan_laporan' => $request->catatan_laporan[$subIndex] ?? null,
                'jenis_laporan' => 'monthly',
                'order_of_the_week' => null,
            ]);
        }

    }

    return redirect()->route('daftarlaporan')->with('success', 'Detail Laporan berhasil disimpan.');
}


    public function getBulanByTahun($tahunId)
    {
        $bulanLaporan = WaktuBulan::where('waktu_tahun_laporan_id', $tahunId)->get();
        return response()->json($bulanLaporan);
    }

    public function createLaporan($bulan_id, $judul_id)
    {
        // Ambil data bulan menggunakan ID yang diberikan
        $bulanLaporan = WaktuBulan::findOrFail($bulan_id);

        // Ambil data judul laporan yang aktif berdasarkan ID yang diberikan
        $judulLaporan = Judul::findOrFail($judul_id);

        $subjudulLaporan = SubJudul::where('judul_laporan_id', $judul_id)
                                ->get();

        // Ambil data detail laporan berdasarkan judul dan bulan, termasuk data subjudul terkait
        $detailLaporan = DetailLaporan::where('waktu_bulan_laporan_id', $bulan_id)
                                    ->where('judul_laporan_id', $judul_id)
                                    ->with(['subjudul', 'judul', 'waktuBulan']) // Pastikan eager loading relasi
                                    ->get();

        // Kirimkan semua data ke view yang sama
        return view('masterlaporan.masukanLaporan', compact('bulanLaporan', 'judulLaporan', 'subjudulLaporan', 'detailLaporan'));
    }

    public function createLaporanMonthly($year, $bulan_id, $judul_id)
    {
        // Ambil data judul laporan yang aktif berdasarkan ID yang diberikan
        $judulLaporan = Judul::findOrFail($judul_id);

        // Ambil data detail laporan berdasarkan judul dan bulan, termasuk data subjudul terkait
        $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_id)
            ->whereRaw('year(start_time) = ?', $year)
            ->whereRaw('year(end_time) = ?', $year)
            ->with(['subjudul', 'judul', 'waktuTahun'])
            ->whereHas('subjudul', function ($query) use ($judul_id) {
                $query->where('judul_laporan_id', $judul_id);
            })
            ->whereHas('subjudul.detailLaporan', function ($query) use ($judul_id, $year){
                $query->where('detail_laporan.judul_laporan_id', $judul_id)
                    ->whereRaw('year(detail_laporan.start_time) = ?', $year)
                    ->whereRaw('year(detail_laporan.end_time) = ?', $year);
            })
            ->whereHas('judul', function ($query) use ($judul_id) {
                $query->where('id', $judul_id);
            })
            ->get();

        $nameBulan = $this->getMonthly($bulan_id);
        $bulanLaporan = [
            'id' => $bulan_id,
            'waktu_bulan_laporan' => $nameBulan,
        ];

        // Kirimkan semua data ke view yang sama
        return view('masterlaporan.masukanLaporanBulanan', compact('year', 'bulanLaporan', 'judulLaporan', 'detailLaporan'));
    }

public function editLaporanBulanan(Request $request, $year, $judul_id, $bulan_id, $detail_id)
{
    // Ambil data Judul Laporan berdasarkan ID
    $judulLaporan = Judul::findOrFail($judul_id);
    // Ambil data Detail Laporan berdasarkan kriteria tertentu
    $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_id)
        ->whereRaw('year(start_time) = ?', $year)
        ->whereRaw('year(end_time) = ?', $year)
        ->with(['subjudul', 'judul', 'waktuTahun'])
        ->whereHas('subjudul', function ($query) use ($judul_id) {
            $query->where('judul_laporan_id', $judul_id);
        })
        ->whereHas('subjudul.detailLaporan', function ($query) use ($judul_id, $year){
            $query->where('detail_laporan.judul_laporan_id', $judul_id)
                ->whereRaw('year(detail_laporan.start_time) = ?', $year)
                ->whereRaw('year(detail_laporan.end_time) = ?', $year);
        })
        ->whereHas('judul', function ($query) use ($judul_id) {
            $query->where('id', $judul_id);
        })
        ->get();
    // Menentukan nama bulan dari ID bulan
    $bulanLaporan = [
        'id' => $bulan_id,
        'waktu_bulan_laporan' => $this->getMonthly($bulan_id),  // Memanggil fungsi getMonthly untuk mendapatkan nama bulan
    ];
    return view('masterlaporan.editLaporanBulanan', compact('judulLaporan','bulanLaporan', 'detailLaporan', 'year'));
}

    public function updateLaporanBulanan(Request $request, $year, $judul_id, $bulan_id, $detail_id)
    {
        try {
             DB::beginTransaction();

            $validatedData = $request->validate([
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'videos.*' => 'nullable|url',
                'catatan.*' => 'nullable|string|max:255',
                'is_monthly' => 'required'
            ]);

            $bulanLaporanByDetailID = BulanLaporan::where('detail_id', $detail_id)
                                        ->get();

            foreach($bulanLaporanByDetailID as $index => $object) {
                dd($object);
            }

            // dd($bulanLaporanByDetailID);

            // if ($request->videos != null && count($request->videos) > 0) {
            //     foreach ($request->videos as $index => $object) {
            //         $detail_id = $request->detail_id[$index];
            //         $detailIDParam = $detail_id;
            //         // Check if there's a photo for this subjudul
            //         BulanLaporan::create([
            //             'detail_id' => $detail_id,
            //             'week' => $request->waktu_minggu_laporan_id,
            //             'judul_laporan_id' => $request->judul_laporan_id,
            //             'subjudul_laporan_id' => $index,
            //             'tipe_laporan' => 'video',
            //             'path_storage' => $object,
            //         ]);
            //     }
            // }


            return view('masterlaporan.masukanLaporanBulanan', compact('year', 'judul_id', 'bulan_id'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('update laporan with file in controller have error: {error}: {judul_id}: {detail_id}',
                [
                    'error' => $e->getMessage(),
                    'judul_id' => $request->judul_laporan_id,
                    'detail_id' => $request->detail_id
                ]
            );
            return redirect()->back()->withErrors($e->getMessage());
        }

    }


    public function masukanLaporan($judul_id, $bulan_id, $minggu_id)
    {
        // Ambil data bulan laporan
        $nameBulan = $this->getMonthly($bulan_id);
        $bulanLaporan = [
            'id' => $bulan_id,
            'waktu_bulan_laporan' => $nameBulan,
        ];

        // Ambil data judul laporan
        $judulLaporan = Judul::findOrFail($judul_id);

        // Ambil subjudul yang terkait dengan judul laporan
        $subjudulLaporan = Subjudul::where('judul_laporan_id', $judul_id)->get();

        // Ambil detail laporan yang sesuai dengan judul, bulan laporan, dan minggu
        $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_id)
//                                        ->where('id', $detail_id)
                                        ->get();

        // Kirim data ke view 'masukanLaporan'
        return view('masterlaporan.masukanLaporan', compact('bulanLaporan', 'judulLaporan', 'subjudulLaporan', 'detailLaporan', 'minggu_id'));
    }

    public function storeLaporanWithFiles(Request $request) {
        $user_id_by_auth = auth()->user()->id;

        $validatedData = $request->validate([
            'judul_laporan_id' => 'required|exists:judul_laporan,id',
            'subjudul_laporan_id.*' => 'exists:subjudul_laporan,id',
            'waktu_bulan_laporan_id' => 'required',
            'detail.*' => 'required',
            'year' => 'required',
            'is_monthly' => 'required',
            'photos.*' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'videos.*' => 'nullable',
        ]);
        $detailIDParam = 0;
        if ($request->photos != null && count($request->photos) > 0) {
            // Loop through subjudul_laporan_id to save catatan_laporan
            foreach ($request->photos as $index => $object) {
                $detail_id = $request->detail_id[$index];
                $detailIDParam = $detail_id;
                // Check if there's a photo for this subjudul
                if ($request->hasFile('photos.' . $index)) {
                    $file = $request->file('photos.' . $index);
                    $raw_path = $file->store('public/assets/img/photos');
                    $image_path = str_replace('public/', '', $raw_path);
                    BulanLaporan::create([
                        'detail_id' => $detail_id,
                        'month' => $request->waktu_bulan_laporan_id,
                        'judul_laporan_id' => $request->judul_laporan_id,
                        'subjudul_laporan_id' => $index,
                        'tipe_laporan' => 'foto',
                        'path_storage' => $image_path,
                        'created_by' => $user_id_by_auth,
                        'year' => $request->waktu_tahun_laporan_id,
                    ]);
                } else {
                    Log::error('store laporan with file in controller have error: {error}: {judul_id}: {detail_id}', ['error' => "photos file doesn't exists", 'judul_id' => $request->judul_laporan_id, 'detail_id' => $request->detail_id]);
                    return redirect()->back()->withErrors("photos file doesn't exists")->withInput();
                }
            }
        }

        if ($request->videos != null && count($request->videos) > 0) {
            foreach ($request->videos as $index => $object) {
                $detail_id = $request->detail_id[$index];
                $detailIDParam = $detail_id;
                // Check if there's a photo for this subjudul
                BulanLaporan::create([
                    'detail_id' => $detail_id,
                    'month' => $request->waktu_bulan_laporan_id,
                    'judul_laporan_id' => $request->judul_laporan_id,
                    'subjudul_laporan_id' => $index,
                    'tipe_laporan' => 'video',
                    'path_storage' => $object,
                    'created_by' => $user_id_by_auth,
                ]);
            }
        }

        return redirect()->route('bulanLaporan', ['year' => $request->year, 'detail_id' => $detailIDParam, 'judul_id' => $request->judul_laporan_id, 'is_monthly' => $request->is_monthly])->with('success', 'Laporan dan file berhasil disimpan.');
    }

    public function showLaporanDetail($judul_laporan_id, $waktu_bulan_laporan_id)
    {
        // Ambil judul laporan yang aktif
        $judulLaporan = JudulLaporan::findOrFail($judul_laporan_id);

        // Ambil subjudul laporan berdasarkan judul dan bulan yang aktif
        $subjudulLaporan = DB::table('subjudul_laporan')
                        ->join('judul_laporan', 'subjudul_laporan.judul_laporan_id', '=', 'judul_laporan.id')
                        ->join('waktu_bulan_laporan', 'subjudul_laporan.waktu_bulan_laporan_id', '=', 'waktu_bulan_laporan.id')
                        ->where('subjudul_laporan.judul_laporan_id', $judul_laporan_id)
                        ->where('subjudul_laporan.waktu_bulan_laporan_id', $waktu_bulan_laporan_id)
                        ->select('subjudul_laporan.*')
                        ->get();


        // Kirim data ke view
        return view('masukanLaporan', compact('judulLaporan', 'subjudulLaporan'));
    }

public function getReportWeekly($year, $judul_id, $bulan_id, $minggu_id) {
        try {
            // Ambil data judul laporan yang aktif berdasarkan ID yang diberikan
            $judulLaporan = Judul::findOrFail($judul_id);

            // Ambil data detail laporan berdasarkan judul dan bulan, termasuk data subjudul terkait
            $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_id)
                ->whereRaw('year(start_time) = ?', $year)
                ->whereRaw('year(end_time) = ?', $year)
                ->with(['subjudul', 'judul', 'waktuTahun'])
                ->whereHas('subjudul', function ($query) use ($judul_id) {
                    $query->where('judul_laporan_id', $judul_id);
                })
                ->whereHas('subjudul.detailLaporan', function ($query) use ($judul_id, $year){
                    $query->where('detail_laporan.judul_laporan_id', $judul_id)
                        ->whereRaw('year(detail_laporan.start_time) = ?', $year)
                        ->whereRaw('year(detail_laporan.end_time) = ?', $year);
                })
                ->whereHas('judul', function ($query) use ($judul_id) {
                    $query->where('id', $judul_id);
                })
                ->get();

            $nameBulan = $this->getMonthly($bulan_id);
            $bulanLaporan = [
                'id' => $bulan_id,
                'waktu_bulan_laporan' => $nameBulan,
            ];

            $weekReport = [
              'id' => $minggu_id,
              'waktu_minggu_laporan' => $this->getWeekly($minggu_id),
            ];

            return view('masterlaporan.masukanLaporanMingguan',
                compact('year', 'judulLaporan', 'bulanLaporan', 'detailLaporan', 'weekReport')
            );
        } catch (\Exception $e) {
            toast('Terjadi Kesalahan', 'error');
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function showWeeklyReports($year, $month, $week, $judul_id) //mingguan
    {
        if (auth()->user()->type == "manager" || auth()->user()->type == "admin") {
            $laporans = MingguanLaporan::with(['judul.details', 'user'])
            ->select(
                'mingguan_laporan.created_by',
                    'mingguan_laporan.status'
                )
                ->where('judul_laporan_id', $judul_id)
                ->where('year', $year)
                ->where('month', $month)
                ->groupBy(
                    'mingguan_laporan.created_by',
                    'mingguan_laporan.status'
                    )
                    ->orderBy('id', 'desc')
                    ->get();
                } else {
                    $laporans = mingguLaporan::with(['judul.details', 'user'])
                ->select(
                    'mingguan_laporan.created_by',
                    'mingguan_laporan.status'
                    )
                ->where('judul_laporan_id', $judul_id)
                ->where('year', $year)
                ->where('month', $month)
                ->where('week', $week)
                ->where('mingguan_laporan.created_by', auth()->user()->id)
                ->groupBy(
                    'mingguan_laporan.created_by',
                    'mingguan_laporan.status'
                )
                ->orderBy('id', 'desc')
                ->get();
            }
        
            // dd($laporans->toArray());
            // dd($laporans->first()->user);
            $judulLaporan = Judul::findOrFail($judul_id);
        
        $monthName = self::getMonthly($month);
        $weeklyNumber = self::getWeekly($week);

        return view('masterlaporan.weekly', compact('judulLaporan', 'laporans', 'year', 'monthName', 'month', 'week'));
    }



    public function createWeeklyReport(Request $request) {
        // dd($request->all());
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'judul_laporan_id' => 'required|exists:judul_laporan,id',
                'subjudul_laporan_id.*' => 'exists:subjudul_laporan,id',
                'waktu_minggu_laporan_id' => 'required',
                'detail.*' => 'required',
                'year' => 'required',
                'month' => 'required|integer|min:1|max:12',
                'is_monthly' => 'required',
                'photos.*' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
                'videos.*' => 'nullable',
            ]);

            $detailIDParam = 0;
            if ($request->photos != null && count($request->photos) > 0) {
                // Loop through subjudul_laporan_id to save catatan_laporan
                foreach ($request->photos as $index => $object) {
                    $detail_id = $request->detail_id[$index];
                    $detailIDParam = $detail_id;
                    // Check if there's a photo for this subjudul
                    if ($request->hasFile('photos.' . $index)) {
                        $file = $request->file('photos.' . $index);
                        $raw_path = $file->store('public/assets/img/photos');
                        $image_path = str_replace('public/', '', $raw_path);
                        MingguanLaporan::create([
                            'detail_id' => $detail_id,
                            'week' => $request->waktu_minggu_laporan_id,
                            'judul_laporan_id' => $request->judul_laporan_id,
                            'subjudul_laporan_id' => $index,
                            'tipe_laporan' => 'foto',
                            'path_storage' => $image_path,
                            'month' => $request->month, 
                            'year' => $request->year,
                            'created_by' => auth()->id(),
                        ]);
                    } else {
                        Log::error('store laporan with file in controller have error: {error}: {judul_id}: {detail_id}', ['error' => "photos file doesn't exists", 'judul_id' => $request->judul_laporan_id, 'detail_id' => $request->detail_id]);
                        return redirect()->back()->withErrors("photos file doesn't exists")->withInput();
                    }
                }
            }

            if ($request->videos != null && count($request->videos) > 0) {
                foreach ($request->videos as $index => $object) {
                    $detail_id = $request->detail_id[$index];
                    $detailIDParam = $detail_id;
                    // Check if there's a photo for this subjudul
                    BulanLaporan::create([
                        'detail_id' => $detail_id,
                        'week' => $request->waktu_minggu_laporan_id,
                        'judul_laporan_id' => $request->judul_laporan_id,
                        'subjudul_laporan_id' => $index,
                        'tipe_laporan' => 'video',
                        'path_storage' => $object,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('mingguLaporan', ['year' => $request->year, 'detail_id' => $detailIDParam, 'bulan_id' => $request->waktu_bulanan_laporan_id, 'judul_id' => $request->judul_laporan_id, 'is_monthly' => $request->is_monthly])->with('success', 'Laporan dan file berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('store laporan with file in controller have error: {error}: {judul_id}: {detail_id}', ['error' => $e->getMessage(), 'judul_id' => $request->judul_laporan_id, 'detail_id' => $request->detail_id]);
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

}
