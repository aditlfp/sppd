<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EslonController;
use App\Http\Controllers\MainSPPDController;
use App\Http\Controllers\PocketMoneyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('main_sppds', MainSPPDController::class);
    Route::get('main_sppds/{main_sppd}/store-bottom', [MainSPPDController::class, 'storeBottom'])->name('main_sppds.store-bottom');
    Route::get('main_sppds/form-bottom/{main_sppd}', [MainSPPDController::class, 'continueSection'])->name('main_sppds.bottom');
    Route::get('main_sppds/{main_sppd}/details', [MainSPPDController::class, 'details'])->name('main_sppds.details');
    Route::get('main_sppds/change/{main_sppd}', [MainSPPDController::class, 'change'])->name('main_sppds.change');
    Route::patch('main_sppds/change/{main_sppd}', [MainSPPDController::class, 'changeUpdate'])->name('main_sppds.updateChange');
});

Route::middleware(['admin'])->group(function () {

    Route::resource('eslons', EslonController::class);
    Route::resource('regions', RegionController::class);
    Route::resource('pocket_moneys', PocketMoneyController::class);
    Route::resource('transportations', TransportationController::class);
});

Route::middleware(['grant_verify'])->group(function () {
    Route::get('/verify/{main_sppd}', [VerifyController::class, 'viewVerify'])->name('verify_page.index');
    Route::patch('/verify/{main_sppd}', [VerifyController::class, 'verify'])->name('verifyUpdate');
});

require __DIR__.'/auth.php';
