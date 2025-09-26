<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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
Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('items.detail');

Route::get('/register', [AuthController::class, 'create'])->name('auth.register');
Route::post('/register', [AuthController::class, 'store']);

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authenticate']);

// メール認証
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// メール送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->SendEmailVerificationNotification();
    return back()->with('message', '認証メールを送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// メール内での認証リンク処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profiles.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Webhook受信
Route::post('/stripe/webhook', [PurchaseController::class, 'storePurchase'])->withoutMiddleware(VerifyCsrfToken::class, Authenticate::class);

Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/item/{item_id}/like', [ItemController::class, 'like'])->name('items.like');
    Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])->name('items.comment');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('profiles.show');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/sell', [ItemController::class, 'showExhibition'])->name('items.exhibition');
    Route::post('/sell', [ItemController::class, 'storeExhibition']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase'])->name('purchases.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'transitionToStripe'])->name('purchases.stripe');

    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchases.address');
    Route::patch('/purchase/address/{item_id}', [PurchaseController::class, 'update']);
});
