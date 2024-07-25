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

});
