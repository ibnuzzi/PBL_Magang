<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
<<<<<<< HEAD
    return redirect('/admin/login');
})->name('login');

// Logbook Supervisor Public Link Approval Routes
Route::get('/logbook/approve/{token}', [App\Http\Controllers\LogbookApprovalController::class, 'show'])->name('logbook.approve');
Route::post('/logbook/approve/{token}', [App\Http\Controllers\LogbookApprovalController::class, 'process'])->name('logbook.approve.process');

// Logbook Mahasiswa Print Report Route
Route::get('/mahasiswa/logbook/print', [App\Http\Controllers\LogbookPrintController::class, 'print'])->name('mahasiswa.logbook.print')->middleware('auth');

// Surat Magang Secure Download Route
Route::get('/surat/unduh/{suratMagang}', [App\Http\Controllers\SuratDownloadController::class, 'download'])
    ->name('surat.download')
    ->middleware('auth');
=======
    return redirect('/mahasiswa/login');
})->name('login');
>>>>>>> 150635d585532dd09f3f846e9ec0e0a8246e5232
