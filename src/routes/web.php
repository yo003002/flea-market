<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;


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
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::get('/sell', [ItemController::class, 'create']);
Route::post('/sell', [ItemController::class, 'store']);


Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address']);







Route::middleware('auth')->group(function () {
    
    //マイページ・プロフィール編集
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    
    
    Route::get('/mypage', [MypageController::class, 'index']);
    Route::get('/mypage/sell', [MypageController::class, 'sold']);
});

Route::get('/register', function () {
    return view('auth.register');
});
