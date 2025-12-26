<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;


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




Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    
    //いいね
    Route::post('/items/{item_id}/like', [LikeController::class, 'store'])->name('items.like');

    //コメント
    Route::post('/items/{item_id}/comment', [CommentController::class, 'store'])->name('items.comment');

    
    //プロフィール編集
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);

    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    
    
    
    
    //出品
    Route::get('/sell', [ItemController::class, 'create']);
    
    Route::post('/sell', [ItemController::class, 'store']);
    
    //購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm'])->name('purchase.confirm');
    
    
    
    Route::post('/purchase/{item_id}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    
    //プロフィール
    Route::get('/mypage', [ProfileController::class, 'index']);
    Route::get('/mypage/sell', [ProfileController::class, 'sold']);
    
    //住所変更
    Route::get('/purchase/{item_id}/address', 
    [PurchaseController::class, 'editAddress'])->name('purchase.address');
    
    Route::post('/purchase/{item_id}/address', 
    [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    
});