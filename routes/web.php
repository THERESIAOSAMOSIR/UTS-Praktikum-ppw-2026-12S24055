<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\SirkulasiController;

Route::middleware('guest')->group(function () {
    Route::get('/equipments', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/equipments/{id}', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/loans', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/loans/create', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [BukuController::class, 'index'])->name('bukus.index');

    Route::get('/sirkulasi', [SirkulasiController::class, 'index'])->name('sirkulasi.index');
    Route::post('/sirkulasi/pinjam', [SirkulasiController::class, 'pinjamStore'])->name('sirkulasi.pinjam');
    Route::patch('/sirkulasi/kembali/{id}', [SirkulasiController::class, 'kembalikanStore'])->name('sirkulasi.kembali');
});