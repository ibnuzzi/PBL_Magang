<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KoordinatorController;

Route::get('/', function () {
    return view('welcome');
});

Route::patch('/koordinator/{id}/status', [KoordinatorController::class, 'updateStatus'])->name('koordinator.updateStatus');
Route::resource('koordinator', KoordinatorController::class);

Route::prefix('koordinator')->name('koordinator.')->group(function () {
    // Route::resource('dashboard', \App\Http\Controllers\Koordinator\DashboardController::class);
    // Route::resource('verifikasi', \App\Http\Controllers\Koordinator\VerifikasiController::class);
    // Route::resource('pendaftaran', \App\Http\Controllers\Koordinator\PendaftaranController::class);
    // Route::resource('perusahaan', \App\Http\Controllers\Koordinator\PerusahaanController::class);
    // Route::resource('logbook', \App\Http\Controllers\Koordinator\LogbookController::class);
    // Route::resource('penilaian', \App\Http\Controllers\Koordinator\PenilaianController::class);
    // Route::resource('laporan', \App\Http\Controllers\Koordinator\LaporanController::class);
    // Route::resource('pengaturan', \App\Http\Controllers\Koordinator\PengaturanController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
});

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::resource('dashboard', \App\Http\Controllers\Mahasiswa\DashboardController::class);
    // Route::resource('logbook', \App\Http\Controllers\Mahasiswa\LogbookController::class);
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');
