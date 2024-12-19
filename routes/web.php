<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaktuController;
use App\Http\Controllers\JudulController;
use App\Http\Controllers\MasterLaporanController;
use App\Http\Controllers\HasilController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/tambahUsers', [UserController::class, 'create'])->name('tambahUsers');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

Route::get('/upload', [UploadImageController::class, 'index'])->name('upload.index');
Route::post('/upload', [UploadImageController::class, 'save'])->name('upload.save');
/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:user'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

});
    Route::get('/superadmin/dataCabang', [AdminController::class, 'dataCabang'])->name('dataCabang');
        Route::get('/superadmin/tambahCabang', [AdminController::class, 'tambahCabang'])->name('tambahCabang');

        Route::get('/cabangs', [AdminController::class, 'index'])->name('cabangs.index');
        Route::get('/cabangs/{id}', [AdminController::class, 'show'])->name('cabangs.show');
        Route::post('/cabang', [AdminController::class,'store'])->name('cabang.store');
        Route::delete('/cabangs/{id}', [AdminController::class, 'destroy'])->name('cabangs.destroy');
        Route::get('cabangs/{id}/edit', [AdminController::class, 'edit'])->name('cabangs.edit');
        Route::put('cabangs/{id}', [AdminController::class, 'update'])->name('cabangs.update');
        Route::get('/superadmin/cariCabang', [AdminController::class, 'cari'])->name('cariCabang');




/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:manager'])->group(function () {

    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});

Route::get('/master-laporan/daftar-laporan', [MasterlaporanController::class, 'daftarlaporan'])->name('melihatLaporan');

/*------------------------------------------
--------------------------------------------
Waktu route
--------------------------------------------
--------------------------------------------*/

//Tahun//
Route::get('/waktu/waktuTahunan', [WaktuController::class, 'waktuTahunan'])->name('waktuTahunan');
Route::get('/waktu/tambahwaktuTahun', [WaktuController::class, 'tambahTahun'])->name('tambahTahun');
Route::post('/waktu/waktuTahunan/store', [WaktuController::class, 'store'])->name('waktuTahunan.store');
Route::get('/waktu/waktuTahunan/{id}/edit', [WaktuController::class, 'edit'])->name('waktuTahunan.edit');
Route::put('/waktu/waktuTahunan/{id}', [WaktuController::class, 'update'])->name('waktuTahunan.update');
Route::delete('/waktu/waktuTahunan/{id}', [WaktuController::class, 'destroy'])->name('waktuTahunan.destroy');

//Bulan//
Route::get('/waktu-bulanan/{tahunId}', [WaktuController::class, 'waktuBulanan'])->name('waktuBulanan');
Route::get('/tambah-bulan/{tahunId}', [WaktuController::class, 'tambahBulan'])->name('tambahBulan');
Route::post('/store-bulan/{tahunId}', [WaktuController::class, 'storeBulan'])->name('storeBulan');
Route::get('/edit-bulan/{tahunId}/{bulanId}', [WaktuController::class, 'editBulan'])->name('waktuBulanan.edit');
Route::put('/update-bulan/{tahunId}/{bulanId}', [WaktuController::class, 'updateBulan'])->name('waktuBulanan.update');
Route::delete('/delete-bulan/{tahunId}/{bulanId}', [WaktuController::class, 'destroyBulan'])->name('waktuBulanan.destroy');
Route::get('/waktu/bulan/{tahunId}/edit/{bulanId}', [WaktuController::class, 'editBulan'])->name('editBulan');
Route::delete('/waktu/bulan/{tahunId}/delete/{bulanId}', [WaktuController::class, 'destroyBulan'])->name('destroyBulan');


/*------------------------------------------
--------------------------------------------
Judul route
--------------------------------------------
--------------------------------------------*/
//Judul route//
Route::get('/bagianlaporan/judulLaporan', [JudulController::class, 'judullaporan'])->name('judullaporan');

Route::get('/bagianlaporan/judul-laporan', [JudulController::class, 'judullaporan'])->name('judulLaporan');
Route::get('/bagianlaporan/judul-laporan/tambah', [JudulController::class, 'tambahJudul'])->name('tambahJudul');
Route::post('/bagianlaporan/judul/store', [JudulController::class, 'store'])->name('judul.store');
Route::put('/bagianlaporan/judul-laporan/{id}', [JudulController::class, 'update'])->name('updateJudul'); //update new
Route::get('/bagianlaporan/judul-laporan/{id}/edit', [JudulController::class, 'edit'])->name('editJudul');
Route::put('/bagianlaporan/judul-laporan/{id}', [JudulController::class, 'update'])->name('updateJudul');
Route::delete('/bagianlaporan/judul-laporan/{id}', [JudulController::class, 'destroy'])->name('deleteJudul');



// SubJudul routes
Route::get('/bagianlaporan/judul-laporan/{id}/subjudul', [JudulController::class, 'showSubjudul'])->name('subjudul.laporan');
Route::get('/bagianlaporan/judul-laporan/{id}/subjudul/create', [JudulController::class, 'createSubjudul'])->name('subjudul.create');
Route::post('/bagianlaporan/judul-laporan/{id}/subjudul', [JudulController::class, 'storeSubjudul'])->name('subjudul.store');
Route::get('/bagianlaporan/subjudul/{id}/edit', [JudulController::class, 'editSubjudul'])->name('subjudul.edit');
Route::put('/bagianlaporan/subjudul/{id}', [JudulController::class, 'updateSubjudul'])->name('subjudul.update');
Route::delete('/bagianlaporan/subjudul/{id}', [JudulController::class, 'destroySubjudul'])->name('subjudul.destroy');


/*------------------------------------------
--------------------------------------------
Master Laporan Routes List
--------------------------------------------
--------------------------------------------*/
Route::get('/masterlaporan/daftarLaporan', [MasterlaporanController::class, 'daftarlaporan'])->name('daftarlaporan');

Route::get('/mainLaporan/{id}', [MasterlaporanController::class, 'showMainLaporan'])->name('mainLaporan');
// Route::get('/laporan/{id}/detail', [LaporanController::class, 'detailLaporan'])->name('detailLaporan'); //route detail beside rancang
Route::get('/getSubjudulLaporan/{id}', [MasterlaporanController::class, 'getSubjudulLaporan']);
Route::post('/masterlaporan/store', [MasterLaporanController::class, 'store'])->name('detailLaporan.store');
Route::get('/getBulanByTahun/{tahunId}', [MasterLaporanController::class, 'getBulanByTahun']);

/*------------------------------------------
--------------------------------------------
Master Laporan Routes List
--------------------------------------------
--------------------------------------------*/


Route::get('/masterlaporan/judulLaporan', [MasterLaporanController::class, 'showJudulLaporan'])->name('judulLaporan');

Route::get('/tahunLaporan/{id}', [MasterLaporanController::class, 'showYearsByJudul'])->name('tahunLaporan');
Route::get('/bulanLaporan/{year}/{detail_id}/{judul_id}', [MasterLaporanController::class, 'bulanLaporan'])->name('bulanLaporan');
Route::get('/minggu-laporan/{year}/{judul_id}/{detail_id}/{bulan_id}', [masterlaporanController::class, 'mingguLaporan'])->name('mingguLaporan'); //route laporan mingguan
Route::get('/laporan-mingguan/masukan/{judul_id}/{bulan_id}/{minggu_id}', [MasterLaporanController::class, 'masukanLaporan'])->name('masukanLaporan');
Route::get('/laporan/mingguan/{year}/{judul_id}/{bulan_id}/{minggu_id}', [MasterLaporanController::class, 'getReportWeekly'])->name('getReportWeekly'); //action
// Route untuk edit laporan mingguan
Route::get('/laporan/mingguan/edit/{year}/{judul_id}/{bulan_id}/{minggu_id}', [MasterLaporanController::class, 'editLaporanMingguan'])->name('editLaporanMingguan');

// Route untuk update laporan mingguan
Route::put('/minggu-laporan/update/{id}', [MasterLaporanController::class, 'updateWeeklyReport'])->name('updateWeeklyReport');

// Route untuk melihat laporan mingguan
Route::get('/masterlaporan/weekly/{year}/{bulan_id}/{minggu_id}/{judul_id}', [MasterLaporanController::class, 'showWeeklyReports'])->name('showWeeklyReports');

Route::get('/masukanlaporan/{year}/{bulan_id}/{judul_id}', [MasterLaporanController::class, 'createLaporanMonthly'])->name('masukanLaporanBulanan'); //action
Route::get('/masterlaporan/laporan/edit/{year}/{judul_id}/{bulan_id}/{detail_id}', [MasterLaporanController::class, 'editLaporanBulanan'])->name('editLaporanBulanan');
Route::post('/masterlaporan/{year}/{judul_id}/{bulan_id}/{detail_id}/update', [MasterLaporanController::class, 'updateLaporanBulanan'])->name('updateLaporanBulanan');
// Route::get('/masterlaporan/{year}/{judul_id}/{bulan_id}/edit', [MasterLaporanController::class, 'editLaporanBulanan'])->name('masterlaporan.editLaporanBulanan');
Route::delete('/masukanlaporan/{year}/{judul_id}/{bulan_id}/{detail_id}/delete', [MasterLaporanController::class, 'deleteLaporanMonthly'])->name('deleteLaporanBulanan');

Route::get('/masterlaporan/all-laporan-bulanan/{year}/{month}/{judul_id}', [MasterLaporanController::class, 'showAllMonthlyReport'])->name('showAllMonthlyReport'); //action

Route::post('/storeLaporanWithFiles', [MasterLaporanController::class, 'storeLaporanWithFiles'])->name('storeLaporanWithFiles');
Route::post('/laporan/mingguan', [MasterLaporanController::class, 'createWeeklyReport'])->name('createWeeklyReport');
Route::get('/laporan/{id}', [MasterLaporanController::class, 'detailLaporan'])->name('catatanLaporan');
Route::post('/laporan/store', [MasterLaporanController::class, 'store'])->name('storeLaporan');

/*------------------------------------------
--------------------------------------------
Hasil Laporan Routes List
--------------------------------------------
--------------------------------------------*/

Route::get('/hasillaporan', [HasilController::class, 'index'])->name('hasilLaporan.index');
Route::get('/lihathasilTahun/{id}', [HasilController::class, 'showYearsByJudul'])->name('lihathasilTahun');
// Route::get('/list-laporan', [HasilController::class, 'listLaporan'])->name('listLaporan');


// Rute untuk laporan mingguan
Route::get('/hasillaporan/mingguan/{judul_id}', [HasilController::class, 'showDetailMingguan'])->name('hasilLaporanmingguan.detail');

// Rute untuk laporan bulanan
Route::get('/hasillaporan/bulanan/{judul_id}', [HasilController::class, 'showDetailBulanan'])->name('hasilLaporanbulanan.detail');

// Rute untuk detail laporan bulanan
// Route::get('/detaillaporanbulanan/{judul_id}/{bulan_id}', [HasilController::class, 'detailLaporanBulanan'])->name('detaillaporanbulanan'); gajadi
// Route::get('/hasillaporan/{cabang_id}', [HasilController::class, 'lihatLaporanCabang'])->name('hasillaporan.lihatLaporanCabang');
// // Route to store the report with files
// Route::post('/store-laporan', [HasilController::class, 'storeLaporanWithFiles'])->name('storeLaporanWithFiles');
Route::get('/masukan-laporan/{judul_laporan_id}/{waktu_bulan_laporan_id}', [HasilController::class, 'showMasukanLaporan'])->name('masukanLaporan');
//Route::post('/store-laporan-with-files', [HasilController::class, 'storeLaporanWithFiles'])->name('storeLaporanWithFiles');



// Rute untuk detail laporan mingguan
// Route::get('/detaillaporanmingguan/{judul_id}/{bulan_id}/{minggu_id}', [HasilController::class, 'detailLaporanMingguan'])->name('detaillaporanMingguan');


Route::get('/hasillaporan/listLaporan', [HasilController::class, 'showJudulLaporan'])->name('hasiljudulLaporan');
Route::get('/hasillaporan/lihathasilTahun/{id}', [HasilController::class, 'showYearsByJudul'])->name('hasiltahunLaporan');
Route::get('/hasillaporan/lihathasilBulan/{year}/{detail_id}/{judul_id}', [HasilController::class, 'hasilbulanLaporan'])->name('hasilbulanLaporan');

// Rute sementara untuk tampilan aproval untuk detail laporan mingguan
Route::get('/lihathasilLaporancabang/{year}/{bulan_id}', [HasilController::class, 'getApprovalMonthly'])->name('getApprovalMonthly');
Route::post('/approvalMonthly', [HasilController::class, 'approvalMonthly'])->name('approvalMonthly');
// Route::get('/masukanlaporan/{year}/{bulan_id}/{judul_id}/{detail_id}', [MasterLaporanController::class, 'createLaporanMonthly'])->name('masukanLaporanBulanan'); // Ubah parameter agar lebih jelas
//Route::get('/hasillaporan/lihathasilBulan/approval/{year}/{bulan_id}/{judul_id}/{detail_id}', [HasilController::class, 'approvalResultMonthly'])->name('approvalResultMonthly'); // Ubah parameter agar lebih jelas

Route::get('/hasillaporan/lihathasilBulan/approval/{year}/{bulan_id}/{judul_id}', [HasilController::class, 'listCabang'])->name('listCabang');


Route::get('/hasillaporan/listhasilLaporan', [HasilController::class, 'showListhasil'])->name('listhasil');
