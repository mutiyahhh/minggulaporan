<?php

namespace App\Http\Controllers;

use App\Models\BulanLaporan;
use App\Models\MingguanLaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\DetailLaporan;
use App\Models\Judul;
use App\Models\SubJudul;
use App\Models\WaktuBulan;
use App\Models\WaktuTahun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class HasilController extends Controller
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
        return view('hasillaporan.listLaporan', compact('judulLaporan'));
        // return view('masterlaporan.daftarLaporan', compact('judul_laporan'));
    }

    public function showJudulLaporan(Request $request)
    {
        // 0 -> weekly
        // 1 -> monthly
        $is_monthly = $request->query('is_monthly');

        if ($is_monthly == 0) {

            $mingguanLaporan = MingguanLaporan::groupBy('judul_laporan_id')
                ->select('judul_laporan_id')
                ->get();

            $mingguanLaporanListID = [];
            foreach ($mingguanLaporan as $mingguan) {
                array_push($mingguanLaporanListID, $mingguan->judul_laporan_id);
            }

            // Ambil semua judul laporan dari database
            $judulLaporan = Judul::with(['details'])
                ->whereHas('details', function ($query) {
                    $query->where('jenis_laporan', 'weekly');
                })
                ->whereIn('id', $mingguanLaporanListID)
                ->get();
        } else {
            $bulanLaporan = BulanLaporan::groupBy('judul_laporan_id')
                ->select('judul_laporan_id')
                ->get();

            $bulanLaporanListID = [];
            foreach ($bulanLaporan as $bulan) {
                array_push($bulanLaporanListID, $bulan->judul_laporan_id);
            }

            $judulLaporan = Judul::with(['details'])
                ->whereHas('details', function ($query) {
                    $query->where('jenis_laporan', 'monthly');
                })
                ->whereIn('id', $bulanLaporanListID)
                ->get();
        }
        return view('hasillaporan.listLaporan', compact('judulLaporan'));
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
        $day_time_now = Carbon::now()->format('d');
        foreach ($this->masterMonth() as $indexMonth => $month) {
            foreach ($detailLaporanGroupBy as $detailLaporan) {
                $startDate = $detailLaporan->start_time;
                $endDate = $detailLaporan->end_time;
                $startMonth = Carbon::parse($startDate)->format('m');
                $endMonth = Carbon::parse($endDate)->format('m');
                $endDayMonth = Carbon::parse($endDate)->format('d');

                if ($startMonth == $indexMonth) {
                    if ((int)$day_time_now <= (int)$endDayMonth) {
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
                     if ((int)$day_time_now <= (int)$endDayMonth) {
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
        $listWeeklyReporting = collect($listWeeklyReporting)->sortBy('week')->toArray();

//        dd($detailLaporan);

        // Kirim data ke view
        return view('masterlaporan.mingguLaporan', compact('year', 'judulLaporan', 'bulanLaporan', 'listWeeklyReporting', 'detailLaporan'));
    }


    public function index(Request $request)
    {
        $isMonthly = $request->input('is_monthly', 0); // Default to 0 (weekly)

        // Query for getting report titles
        $judulLaporan = Judul::where('is_monthly', $isMonthly)->get();

        return view('listLaporan', compact('judulLaporan'));
    }


    public function showTahunLaporan($id, Request $request)
{
    $isMonthly = $request->input('is_monthly', 0); // Mengambil nilai dari query parameter

    // Logika untuk mengambil data laporan berdasarkan $id dan $isMonthly
    // Misalnya, ambil data berdasarkan Judul dengan ID yang diberikan
    $judul = Judul::find($id);

    $detailLaporanGroupBy = DB::table('detail_laporan')
            ->selectRaw('detail_laporan.id, detail_laporan.start_time, detail_laporan.end_time')
            ->where('judul_laporan_id', $id)
            ->groupBy('detail_laporan.id', 'detail_laporan.start_time', 'detail_laporan.end_time')
            ->get();

    // Kembalikan view dengan data yang relevan
    $monthAllowedInput = [];
        $day_time_now = Carbon::now()->format('d');
        foreach ($this->masterMonth() as $indexMonth => $month) {
            foreach ($detailLaporanGroupBy as $detailLaporan) {
                $startDate = $detailLaporan->start_time;
                $endDate = $detailLaporan->end_time;
                $startMonth = Carbon::parse($startDate)->format('m');
                $endMonth = Carbon::parse($endDate)->format('m');
                $endDayMonth = Carbon::parse($endDate)->format('d');

                if ($startMonth == $indexMonth) {
                    if ((int)$day_time_now <= (int)$endDayMonth) {
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
                     if ((int)$day_time_now <= (int)$endDayMonth) {
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
    return view('hasillaporan.lihathasilBulan', compact('judul', 'isMonthly', 'listMonthInput'));
}

// class HasilController extends Controller
// {
//     public function lihataproval()
// {
//     // Fetch the necessary data here, for example:
//     $DetailLaporan = DetailLaporan::all(); // Example model

//     // Return the view with the data
//     return view('hasillaporan.lihathasilLaporancabang', compact('laporanCabang'));
// }

//     public function index(Request $request)
//     {
//         // Ambil pilihan jenis laporan dari request
//         $isMonthly = $request->input('is_monthly', 0); // Default ke 0 (mingguan)

//         // Query untuk mendapatkan judul laporan
//         $judulLaporan = Laporan::where('is_monthly', $isMonthly)->get();

//         return view('listLaporan', compact('judulLaporan'));
//     }

//     public function listLaporan(Request $request)
//     {
//         // Ambil nilai is_monthly dari request, default ke 0 (mingguan)
//         $isMonthly = $request->input('is_monthly', 0);

//         // Query untuk mendapatkan judul laporan sesuai dengan pilihan
//         // Sesuaikan query ini dengan struktur tabel Anda
//         $judulLaporan = JudulLaporan::where('is_monthly', $isMonthly)->get();

//         return view('listLaporan', compact('judulLaporan'));
//     }


    // public function index() tanpa pemisahan bulanan mingguan
    // {
    //     $uniqueJudulLaporan = DetailLaporan::with('judul')
    //         ->get()
    //         ->unique('judul_laporan_id')
    //         ->pluck('judul');

    //     return view('hasillaporan.listLaporan', compact('uniqueJudulLaporan'));
    // }

    public function showDetailMingguan($judul_id)
    {
        $judulLaporan = Judul::with('details.subjudul')->find($judul_id);

        if (!$judulLaporan) {
            abort(404, 'Judul Laporan Mingguan not found');
        }

        return view('hasillaporan.lihatlaporanMingguan', compact('judulLaporan'));
    }

    public function showDetailBulanan($judul_id)
    {
        $judulLaporan = Judul::with('details.subjudul')->find($judul_id);

        if (!$judulLaporan) {
            abort(404, 'Judul Laporan Bulanan not found');
        }

        return view('hasillaporan.lihatlaporanBulanan', compact('judulLaporan'));
    }

    public function detailLaporanBulanan($judul_id, $bulan_id)
    {
        // Implementasi logika untuk detail laporan bulanan
        // Misalnya: ambil data berdasarkan judul_id dan bulan_id
        return view('hasillaporan.detaillaporanBulanan', compact('judul_id', 'bulan_id'));
    }

    public function detailLaporanMingguan($judul_id, $bulan_id, $minggu_id)
    {
        // Implementasi logika untuk detail laporan mingguan
        // Misalnya: ambil data berdasarkan judul_id, bulan_id, dan minggu_id
        return view('hasillaporan.detaillaporanMingguan', compact('judul_id', 'bulan_id', 'minggu_id'));
    }


    public function showDetailLaporanBulanan($judul_id, $bulan_id)
    {
        // Ambil detail laporan bulanan berdasarkan id judul laporan dan bulan yang dipilih
        $judulLaporan = Judul::with('details.subjudul')->find($judul_id);

        if (!$judulLaporan) {
            abort(404, 'Judul Laporan Bulanan not found');
        }

        return view('hasillaporan.hasilLaporanBulanan', compact('judulLaporan', 'bulan_id'));
    }

    // public function showMasukanLaporan($judul_laporan_id, $waktu_bulan_laporan_id)
    // {
    //     // Ambil data berdasarkan judul laporan dan waktu bulan laporan
    //     $judulLaporan = JudulLaporan::find($judul_laporan_id);
    //     $bulanLaporan = WaktuBulanLaporan::find($waktu_bulan_laporan_id);

    //     // Ambil detail laporan berdasarkan judul_laporan_id
    //     $detailLaporan = DetailLaporan::where('judul_laporan_id', $judul_laporan_id)->get();

    //     // Ambil bulan laporan berdasarkan detail laporan
    //     $bulanLaporanByDetail = BulanLaporan::whereIn('detail_id', $detailLaporan->pluck('id'))->get();

    //     // Dapatkan tahun untuk input hidden
    //     $year = date('Y');

    //     // Pastikan data sudah ada dan diteruskan ke view
    //     return view('hasillaporan.masukanLaporan', compact('judulLaporan', 'bulanLaporan', 'detailLaporan', 'bulanLaporanByDetail', 'year'));
    // }

    // public function lihatLaporanCabang($cabang_id)
    // {
    //     $judulLaporan = Judul::where('cabang_id', $cabang_id)->first();
    //     $bulanLaporan = BulanLaporan::where('cabang_id', $cabang_id)->first();
    //     $detailLaporan = DetailLaporan::where('cabang_id', $cabang_id)->get();
    //     $bulanLaporanByDetail = BulanLaporan::where('cabang_id', $cabang_id)->get(); // Assuming this gets monthly data

    //     return view('hasillaporan.lihathasilLaporancabang', compact('judulLaporan', 'bulanLaporan', 'detailLaporan', 'bulanLaporanByDetail'));
    // }

    public function showDetailLaporanMingguan($judul_id, $bulan_id, $minggu_id)
    {
        // Ambil detail laporan mingguan berdasarkan id judul laporan, bulan, dan minggu yang dipilih
        $judulLaporan = Judul::with('details.subjudul')->find($judul_id);

        if (!$judulLaporan) {
            abort(404, 'Judul Laporan Mingguan not found');
        }

        return view('hasillaporan.hasilLaporanMingguan', compact('judulLaporan', 'bulan_id', 'minggu_id'));
    }

    // public function showJudulLaporan() {
    //     // Ambil semua judul laporan dari database
    //     $judulLaporan = Judul::all();

    //     // Tampilkan view 'judulLaporan' dan kirimkan data judul laporan
    //     return view('hasillaporan.listLaporan', compact('judulLaporan'));
    // }

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
        return view('hasillaporan.lihathasilTahun', compact('detailLaporan', 'judul'));
    }


    public function hasilbulanLaporan($year, $detail_id, $judul_id)
    {
        $user_id_by_auth = auth()->user()->id;

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
            ->selectRaw('
                bulanan_laporan.created_by as created_by,
                detail_laporan.jenis_laporan,
                MONTH(detail_laporan.start_time) as month,
                bulanan_laporan.status
            ')
            ->join('bulanan_laporan', 'bulanan_laporan.detail_id', '=', 'detail_laporan.id')
            ->where('detail_laporan.jenis_laporan', '=', "monthly")
            ->where('detail_laporan.judul_laporan_id', '=', $judul_id)
            // Tidak perlu membatasi hanya yang approved, kita akan mengambil semua status
            // Hapus baris ini yang membatasi hanya approved
            // ->whereRaw('bulanan_laporan.approved_by is not null')
            ->groupBy(
                'bulanan_laporan.created_by',
                'detail_laporan.jenis_laporan',
                'detail_laporan.start_time',
                'bulanan_laporan.status'
                    )
            ->get();

        $cabangs = \App\Models\Cabang::with('users')->get();

        // Kirim data ke view
        return view('hasillaporan.lihathasilBulan', compact('year', 'judul_laporan', 'detailLaporan', 'cabangs', 'detailLaporanGroupBy'));
    }

    public function approvalResultMonthly($year, $bulan_id, $judul_id, $detail_id) {
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

        // dd($detailLaporan);
        $bulanLaporanByDetail = BulanLaporan::where('judul_laporan_id', $judul_id)
                                    ->get();

        // dd($bulanLaporanByDetail);
        return view('hasillaporan.lihathasilLaporancabang', compact('year', 'bulanLaporan', 'judulLaporan', 'detailLaporan', 'bulanLaporanByDetail'));
    }

    public function getApprovalMonthly($year, $bulan_id, Request $request) {
        $user_id_by_auth = auth()->user()->id;

        if( $request->has('user_id_detail') && $request->has('judul_laporan_id') ) {
            $user_id_detail = $request->query('user_id_detail');
            $judul_laporan_id = $request->query('judul_laporan_id');
            $cabang_by_user = User::with('Cabang')
                ->where('id', $user_id_detail)
                ->get();

            $bulanLaporanByDetail = BulanLaporan::where('created_by', $user_id_detail)
                                ->where('judul_laporan_id', $judul_laporan_id)
                                ->get();

            $cabangByUser = $cabang_by_user[0]->Cabang;
            $cabang_id = $cabang_by_user[0]->Cabang->id;
        } else {
            $cabang_id_query_param = $request->query('cabang_id');
            $judul_laporan_id = $request->query('judul_laporan_id');
            $cabang_by_user = User::with('Cabang')
                ->select('id')
                ->where('cabang_id', $cabang_id_query_param)
                ->get();
            $listUserId = $cabang_by_user->pluck('id');
            $bulanLaporanByDetail = BulanLaporan::whereIn('created_by', $listUserId)
                                    ->where('judul_laporan_id', $judul_laporan_id)
                                    ->get();

            $cByUser = User::with('Cabang')
                ->where('cabang_id', $cabang_id_query_param)
                ->get();
            $cabangByUser = $cByUser[0]->Cabang;
            $cabang_id = $cByUser[0]->Cabang->id;

            // dd($bulanLaporanByDetail);
        }

        $nameBulan = $this->getMonthly($bulan_id);
        $bulanLaporan = [
            'id' => $bulan_id,
            'waktu_bulan_laporan' => $nameBulan,
        ];

        $listDetailId = [];

        foreach ($bulanLaporanByDetail as $detail) {
            $listDetailId[] = $detail->detail_id;
        }
        $detail_id = $bulanLaporanByDetail[0]->detail_id;

        $listDetailId = implode(', ', $listDetailId);

        $detailLaporan = DetailLaporan::with(['subjudul', 'judul', 'waktuTahun'])
            ->where('id', $detail_id)
            ->whereRaw('year(start_time) = ?', $year)
            ->whereRaw('year(end_time) = ?', $year)
            ->get();
        
        if (count($detailLaporan) == 0) {
            return redirect()->back()->withErrors(['message' => 'Data laporan belum diinput.']);
        }

        return view('hasillaporan.lihathasilLaporancabangNew', compact('year', 'bulan_id', 'cabang_id', 'cabangByUser', 'bulanLaporan', 'detailLaporan', 'bulanLaporanByDetail', 'listDetailId'));
    }

    public function getApprovalWeekly($year, $bulan_id, $week, Request $request) {
        $user_id_by_auth = auth()->user()->id;

        if( $request->has('user_id_detail') && $request->has('judul_laporan_id') ) {
            $user_id_detail = $request->query('user_id_detail');
            $judul_laporan_id = $request->query('judul_laporan_id');
            $cabang_by_user = User::with('Cabang')
                ->where('id', $user_id_detail)
                ->get();

            $mingguLaporanByDetail = BulanLaporan::where('created_by', $user_id_detail)
                                ->where('judul_laporan_id', $judul_laporan_id)
                                ->get();

            $cabangByUser = $cabang_by_user[0]->Cabang;
            $cabang_id = $cabang_by_user[0]->Cabang->id;
        } else {
            $cabang_id_query_param = $request->query('cabang_id');
            $judul_laporan_id = $request->query('judul_laporan_id');
            $cabang_by_user = User::with('Cabang')
                ->select('id')
                ->where('cabang_id', $cabang_id_query_param)
                ->get();
            $listUserId = $cabang_by_user->pluck('id');
            $mingguLaporanByDetail = BulanLaporan::whereIn('created_by', $listUserId)
                                    ->where('judul_laporan_id', $judul_laporan_id)
                                    ->get();

            $cByUser = User::with('Cabang')
                ->where('cabang_id', $cabang_id_query_param)
                ->get();
            $cabangByUser = $cByUser[0]->Cabang;
            $cabang_id = $cByUser[0]->Cabang->id;

        }
        dd($mingguLaporanByDetail); 

        $nameBulan = $this->getMonthly($bulan_id);
        $bulanLaporan = [
            'id' => $bulan_id,
            'waktu_bulan_laporan' => $nameBulan,
        ];

        $listDetailId = [];

        foreach ($mingguLaporanByDetail as $detail) {
            $listDetailId[] = $detail->detail_id;
        }
        $detail_id = $mingguLaporanByDetail[0]->detail_id;

        $listDetailId = implode(', ', $listDetailId);

        $detailLaporan = DetailLaporan::with(['subjudul', 'judul', 'waktuTahun'])
            ->where('id', $detail_id)
            ->whereRaw('year(start_time) = ?', $year)
            ->whereRaw('year(end_time) = ?', $year)
            ->get();
        
        if (count($detailLaporan) == 0) {
            return redirect()->back()->withErrors(['message' => 'Data laporan belum diinput.']);
        }

        return view('hasillaporan.lihathasilLaporanCabangMingguan', compact('year', 'bulan_id', 'cabang_id', 'cabangByUser', 'bulanLaporan', 'detailLaporan', 'mingguLaporanByDetail', 'listDetailId'));
    }

    public function listCabang($year, $bulan_id, $judul_id, Request $request)
    {
        // Retrieve the report title based on judul_id
        $judul_laporan = Judul::find($judul_id);

        // Check if the judul_laporan exists
        if (!$judul_laporan) {
            return redirect()->back()->withErrors(['message' => 'Judul laporan tidak ditemukan.']);
        }

        $search = $request->input('search');

        // Retrieve cabangs with optional search
        $cabangs = Cabang::when($search, function ($query, $search) {
            $query->where('nama_cabang', 'LIKE', "%{$search}%");
        })->paginate(10);

        return view('hasillaporan.listCabang', compact('cabangs', 'search', 'judul_laporan', 'year', 'bulan_id'));
    }
//     public function listCabang($year, $bulan_id, $judul_id, $detail_id, Request $request)
// {
//     // Retrieve the report title based on judul_id
//     $judul_laporan = Judul::find($judul_id);


// approval monthly yang auto approve all laporan
     public function approvalMonthly(Request $request)
     {
         try {
             // Handle file uploads, approvals, and other form data
             $validated = $request->validate([
                 'approval_status' => 'required',
                 'judul_id' => 'required',
                 'cabang_id' => 'required',
                 'bulan_id' => 'required',
                 'year' => 'required',
                 'list_detail_id' => 'required',
                 'created_by' => 'required'
             ]);

             $createdBy = $request->created_by;
             $listDetailId = explode(', ', $request->list_detail_id);

             DB::beginTransaction();

             $bulanLaporanByListDetailID = BulanLaporan::whereIn('detail_id', $listDetailId)
                                        ->where('created_by', $createdBy);

             if ($request->approval_status == "approve") {
                 $bulanLaporanByListDetailID->update([
                     'status' => $request->approval_status,
                     'updated_at' => Carbon::now(),
                     'approved_by' => auth()->user()->id,
                 ]);
             } else {
                 $bulanLaporanByListDetailID->update([
                     'status' => $request->approval_status,
                     'updated_at' => Carbon::now(),
                     'rejected_by' => auth()->user()->id,
                 ]);
             }

             DB::commit();
             // Process the form submission as needed
             // Example: Store data in the database
             // Note: Adjust model names, fields, and logic as per your actual database structure
             return redirect()->route('listCabang', ['cabang_id' => $request->cabang_id, 'judul_id' => $request->judul_id, 'year' => $request->year, 'is_monthly' => $request->is_monthly, 'bulan_id' => $request->bulan_id])
                 ->with('status', 'fitur approval berhasil dilakukan');
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('store laporan with file in controller have error: {error}: {judul_id}: {detail_id}',
                 [
                     'error' => $e->getMessage(),
                     'judul_id' => $request->judul_laporan_id,
                     'detail_id' => $request->detail_id,
                 ]);
             return redirect()->back()->withErrors($e->getMessage());
         }
     }
    // public function approvalMonthly(Request $request)
    // {
    //     try {
    //         // Validasi input
    //         $validated = $request->validate([
    //             'approval_status' => 'required',
    //             'judul_id' => 'required',
    //             'cabang_id' => 'required',
    //             'bulan_id' => 'required',
    //             'year' => 'required',
    //             'detail_id' => 'required',
    //             // 'is_monthly' => 'required', // jika diperlukan
    //         ]);

    //         DB::beginTransaction();

    //         // Mendapatkan laporan berdasarkan judul_laporan_id dan detail_id
    //         $detailLaporan = DetailLaporan::where('judul_laporan_id', $request->judul_id)
    //             ->where('id', $request->detail_id)
    //             ->first();

    //         // Mendapatkan BulanLaporan berdasarkan judul_laporan_id dan created_by
    //         $bulanLaporan = BulanLaporan::where('judul_laporan_id', $request->judul_id)
    //             ->where('created_by', $request->created_by)
    //             ->first();

    //         if (!$detailLaporan || !$bulanLaporan) {
    //             return redirect()->back()->withErrors('Laporan tidak ditemukan atau akses ditolak.');
    //         }

    //         // Jika approval statusnya approve
    //         if ($request->approval_status == "approve") {
    //             $detailLaporan->update([
    //                 'status_laporan' => $request->approval_status,
    //                 'updated_at' => Carbon::now(),
    //                 'approved_by' => auth()->user()->id, // ID admin yang approve
    //             ]);

    //             $bulanLaporan->update([
    //                 'status' => $request->approval_status,
    //                 'updated_at' => Carbon::now(),
    //                 'approved_by' => auth()->user()->id, // ID admin yang approve
    //             ]);
    //         } else {
    //             // Jika statusnya reject
    //             $detailLaporan->update([
    //                 'status_laporan' => $request->approval_status,
    //                 'updated_at' => Carbon::now(),
    //                 'rejected_by' => auth()->user()->id, // ID admin yang reject
    //             ]);

    //             $bulanLaporan->update([
    //                 'status' => $request->approval_status,
    //                 'updated_at' => Carbon::now(),
    //                 'rejected_by' => auth()->user()->id, // ID admin yang reject
    //             ]);
    //         }

    //         DB::commit();
    //         return redirect()->route('listCabang', [
    //             'cabang_id' => $request->cabang_id,
    //             'judul_id' => $request->judul_id,
    //             'year' => $request->year,
    //             'bulan_id' => $request->bulan_id
    //         ])->with('status', 'fitur approval berhasil dilakukan');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error while processing laporan: ' . $e->getMessage());
    //         return redirect()->back()->withErrors($e->getMessage());
    //     }
    // }



//     $search = $request->input('search');

//     // Retrieve the authenticated user
//     $user = auth()->user();

//     // Apply logic based on user type
//     if ($user->type === 'admin' || $user->type === 'manager') {
//         // Admin and Manager can see all cabangs
//         $cabangs = Cabang::when($search, function ($query, $search) {
//             $query->where('nama_cabang', 'LIKE', "%{$search}%");
//         })->paginate(10);
//     } else {
//         // Regular User can only see their own branch
//         $cabangs = Cabang::where('user_id', $user->id) // Ensure the 'user_id' column exists in cabang table
//             ->when($search, function ($query, $search) {
//                 $query->where('nama_cabang', 'LIKE', "%{$search}%");
//             })
//             ->paginate(10);
//     }

//     return view('hasillaporan.listCabang', compact('cabangs', 'search', 'judul_laporan', 'year', 'bulan_id', 'detail_id'));
// }
public function showListhasil()
    {
        $cabangs = \App\Models\Cabang::with('users')->get();
        return view('hasillaporan.listhasilLaporan', compact('cabangs'));
    }

}
