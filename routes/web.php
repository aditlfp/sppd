<?php

use App\Http\Controllers\EslonController;
use App\Http\Controllers\MainSPPDController;
use App\Http\Controllers\PocketMoneyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TransportationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['admin'])->group(function () {
    Route::resource('main_sppds', MainSPPDController::class);
    Route::get('main_sppds/{main_sppd}/store-bottom', [MainSPPDController::class, 'storeBottom'])->name('main_sppds.store-bottom');

    Route::resource('eslons', EslonController::class);
    Route::resource('regions', RegionController::class);
    Route::resource('pocket_moneys', PocketMoneyController::class);
    Route::resource('transportations', TransportationController::class);
});

require __DIR__.'/auth.php';
