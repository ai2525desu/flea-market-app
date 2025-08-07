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

// 8/7 23:23app.blade.phpのファイルに関連するルーティングと各ファイルをとりあえず整備して表示できるようにした
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/sell', [ItemController::class, 'showExhibition'])->name('items.exhibition');

Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/mypage', [ProfileController::class, 'show'])->name('profiles.show');
