<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);


Route::group(['middleware' => 'auth:api'], function() {

    //store user
    Route::post('/user', [\App\Http\Controllers\Api\UserControler::class, 'store']);

    //show user
    Route::get('/user', [\App\Http\Controllers\Api\UserControler::class, 'index']);

    //show user by id
    Route::get('/user/{id}', [\App\Http\Controllers\Api\UserControler::class, 'show']);

    //update user
    Route::post('/user/{id}', [\App\Http\Controllers\Api\UserControler::class, 'update']);

    //delete user
    Route::delete('/user/{id}', [\App\Http\Controllers\Api\UserControler::class, 'destroy']);

    //store category
    Route::post('/category', [\App\Http\Controllers\Api\CategoryController::class, 'store']);

    //show category
    Route::get('/category', [\App\Http\Controllers\Api\CategoryController::class, 'index']);

    //show category by id
    Route::get('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);

    //update category
    Route::post('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'update']);

    //delete category
    Route::delete('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'destroy']);

    //store ruangan
    Route::post('/ruangan', [\App\Http\Controllers\Api\RuanganController::class, 'store']);

    //show ruangan
    Route::get('/ruangan', [\App\Http\Controllers\Api\RuanganController::class, 'index']);

    //show ruangan by id
    Route::get('/ruangan/{id}', [\App\Http\Controllers\Api\RuanganController::class, 'show']);

    //update ruangan
    Route::post('/ruangan/{id}', [\App\Http\Controllers\Api\RuanganController::class, 'update']);

    //delete ruangan
    Route::delete('/ruangan/{id}', [\App\Http\Controllers\Api\RuanganController::class, 'destroy']);

    //store kondisi
    Route::post('/kondisi', [\App\Http\Controllers\Api\KondisiController::class, 'store']);

    //show kondisi
    Route::get('/kondisi', [\App\Http\Controllers\Api\KondisiController::class, 'index']);

    //show kondisi by id
    Route::get('/kondisi/{id}', [\App\Http\Controllers\Api\KondisiController::class, 'show']);

    //update kondisi
    Route::post('/kondisi/{id}', [\App\Http\Controllers\Api\KondisiController::class, 'update']);

    //delete kondisi
    Route::delete('/kondisi/{id}', [\App\Http\Controllers\Api\KondisiController::class, 'destroy']);

    // store barang
    Route::post('/barang', [\App\Http\Controllers\Api\BarangController::class, 'store']);

    // show barang
    Route::get('/barang', [\App\Http\Controllers\Api\BarangController::class, 'index']);

    // store barang masuk

    Route::post('/barang-masuk', [\App\Http\Controllers\Api\BarangMasukController::class, 'store']);

    // show barang masuk

    Route::get('/barang-masuk', [\App\Http\Controllers\Api\BarangMasukController::class, 'index']);

    // show barang masuk by id

    Route::get('/barang-masuk/{id}', [\App\Http\Controllers\Api\BarangMasukController::class, 'show']);

    // update barang masuk

    Route::post('/barang-masuk/{id}', [\App\Http\Controllers\Api\BarangMasukController::class, 'update']);

    // delete barang masuk

    Route::delete('/barang-masuk/{id}', [\App\Http\Controllers\Api\BarangMasukController::class, 'destroy']);

    // store barang keluar

    Route::post('/barang-keluar', [\App\Http\Controllers\Api\BarangKeluarController::class, 'store']);

    // show barang keluar

    Route::get('/barang-keluar', [\App\Http\Controllers\Api\BarangKeluarController::class, 'index']);

    // show barang keluar by id

    Route::get('/barang-keluar/{id}', [\App\Http\Controllers\Api\BarangKeluarController::class, 'show']);

    // update barang keluar

    Route::post('/barang-keluar/{id}', [\App\Http\Controllers\Api\BarangKeluarController::class, 'update']);

    // delete barang keluar

    Route::delete('/barang-keluar/{id}', [\App\Http\Controllers\Api\BarangKeluarController::class, 'destroy']);

    // store perbaikan

    Route::post('/perbaikan', [\App\Http\Controllers\Api\PerbaikanController::class, 'store']);

    // show perbaikan

    Route::get('/perbaikan', [\App\Http\Controllers\Api\PerbaikanController::class, 'index']);

    // show perbaikan by id

    Route::get('/perbaikan/{id}', [\App\Http\Controllers\Api\PerbaikanController::class, 'show']);

    // update perbaikan

    Route::post('/perbaikan/{id}', [\App\Http\Controllers\Api\PerbaikanController::class, 'update']);

    // delete perbaikan

    Route::delete('/perbaikan/{id}', [\App\Http\Controllers\Api\PerbaikanController::class, 'destroy']);

    // store barang ruangan

    Route::post('/barang-ruangan', [\App\Http\Controllers\Api\BarangRuanganController::class, 'store']);

    // show barang ruangan

    Route::get('/barang-ruangan', [\App\Http\Controllers\Api\BarangRuanganController::class, 'index']);

    // show barang ruangan by id

    Route::get('/barang-ruangan/{id}', [\App\Http\Controllers\Api\BarangRuanganController::class, 'show']);

    // update barang ruangan

    Route::post('/barang-ruangan/{id}', [\App\Http\Controllers\Api\BarangRuanganController::class, 'update']);

    // delete barang ruangan

    Route::delete('/barang-ruangan/{id}', [\App\Http\Controllers\Api\BarangRuanganController::class, 'destroy']);

    // get ruangan by user id
    Route::get('/ruangan-user', [\App\Http\Controllers\Api\RuanganController::class, 'getRuanganByUser']);

    // report barang masuk
    Route::get('/report-barang-masuk', [\App\Http\Controllers\Api\BarangMasukController::class, 'reportBarangMasuk']);

    // report barang keluar
    Route::get('/report-barang-keluar', [\App\Http\Controllers\Api\BarangKeluarController::class, 'reportBarangKeluar']);

    // report perbaikan
    Route::get('/report-perbaikan', [\App\Http\Controllers\Api\PerbaikanController::class, 'reportPengajuanPerbaikan']);

    // report barang ruangan
    Route::get('/report-barang-ruangan', [\App\Http\Controllers\Api\BarangRuanganController::class, 'reportBarangRuangan']);        

    // getsurat aktif
    Route::get('/surat-aktif', [\App\Http\Controllers\Api\SuratAktif::class, 'index']);

    // store surat aktif
    Route::post('/surat-aktif', [\App\Http\Controllers\Api\SuratAktif::class, 'store']);

    // show surat aktif by id
    Route::get('/surat-aktif/{id}', [\App\Http\Controllers\Api\SuratAktif::class, 'show']);

    // update surat aktif
    Route::post('/surat-aktif/{id}', [\App\Http\Controllers\Api\SuratAktif::class, 'update']);

    // delete surat aktif
    Route::delete('/surat-aktif/{id}', [\App\Http\Controllers\Api\SuratAktif::class, 'destroy']);

    // get surat tugas
    Route::get('/surat-tugas', [\App\Http\Controllers\Api\SuratTugasController::class, 'index']);

    // store surat tugas
    Route::post('/surat-tugas', [\App\Http\Controllers\Api\SuratTugasController::class, 'store']);

    // show surat tugas by id
    Route::get('/surat-tugas/{id}', [\App\Http\Controllers\Api\SuratTugasController::class, 'show']);

    // update surat tugas
    Route::post('/surat-tugas/{id}', [\App\Http\Controllers\Api\SuratTugasController::class, 'update']);

    // delete surat tugas
    Route::delete('/surat-tugas/{id}', [\App\Http\Controllers\Api\SuratTugasController::class, 'destroy']);

    // get surat baik
    Route::get('/surat-baik', [\App\Http\Controllers\Api\SuratBaikController::class, 'index']);

    // store surat baik
    Route::post('/surat-baik', [\App\Http\Controllers\Api\SuratBaikController::class, 'store']);

    // show surat baik by id
    Route::get('/surat-baik/{id}', [\App\Http\Controllers\Api\SuratBaikController::class, 'show']);

    // update surat baik
    Route::post('/surat-baik/{id}', [\App\Http\Controllers\Api\SuratBaikController::class, 'update']);

    // delete surat baik
    Route::delete('/surat-baik/{id}', [\App\Http\Controllers\Api\SuratBaikController::class, 'destroy']);

    // get surat 
    Route::get('/surat', [\App\Http\Controllers\Api\SuratController::class, 'index']);

    // store surat
    Route::post('/surat', [\App\Http\Controllers\Api\SuratController::class, 'store']);

    // show surat by id
    Route::get('/surat/{id}', [\App\Http\Controllers\Api\SuratController::class, 'show']);

    // update surat
    Route::post('/surat/{id}', [\App\Http\Controllers\Api\SuratController::class, 'update']);

    // delete surat
    Route::delete('/surat/{id}', [\App\Http\Controllers\Api\SuratController::class, 'destroy']);
});
