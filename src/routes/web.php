<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/register', [AuthController::class, 'create'])->name('auth.register');
Route::post('/register', [AuthController::class, 'store']);

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authenticate']);

// 認証ミドルウェアの必要な部分は下記に記述。そうすると自動的に未認証の場合はログイン画面に遷移する
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profiles.show');
    Route::get('mypage?tab=sell', [ProfileController::class, 'exhibitionList']);
    Route::get('mypage?tab=buy', [ProfileController::class, 'purchaseList']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/sell', [ItemController::class, 'showExhibition'])->name('items.exhibition');
});
