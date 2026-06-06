<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/jalankan-migrasi', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Migrasi berhasil dijalankan!";
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::get('/forgot-password', [App\Http\Controllers\Auth\LoginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', function () {
    return back()->with('status', 'Link reset password telah dikirim ke email Anda (Simulasi).');
});

// Logbook Supervisor Public Link Approval Routes
Route::get('/logbook/approve/{token}', [App\Http\Controllers\LogbookApprovalController::class, 'show'])->name('logbook.approve');
Route::post('/logbook/approve/{token}', [App\Http\Controllers\LogbookApprovalController::class, 'process'])->name('logbook.approve.process');

// Logbook Mahasiswa Print Report Route
Route::get('/mahasiswa/logbook/print', [App\Http\Controllers\LogbookPrintController::class, 'print'])->name('mahasiswa.logbook.print')->middleware('auth');

// Surat Magang Secure Download Route
Route::get('/surat/unduh/{suratMagang}', [App\Http\Controllers\SuratDownloadController::class, 'download'])
    ->name('surat.download')
    ->middleware('auth');
