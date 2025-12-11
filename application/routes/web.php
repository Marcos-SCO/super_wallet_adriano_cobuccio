<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;


Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);


Route::middleware(['auth'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::post('/wallet/transactions/{transaction}/reverse', [WalletController::class, 'reverse'])->name('wallet.reverse');
});

// Locale switcher
Route::get('locale/{lang}', function ($lang) {
    $available = ['en', 'pt_BR'];

    if (! in_array($lang, $available)) {
        $lang = 'en';
    }

    session(['locale' => $lang]);

    return redirect(url()->previous() ?: route('wallet.index'));
})->name('locale.switch');
