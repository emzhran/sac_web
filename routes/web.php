<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaPenggunaController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('success', 'Email kamu berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi sudah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{lapangan:id}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{lapangan:id}/store', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}/pdf', [RiwayatController::class, 'cetakPdf'])->name('riwayat.pdf');
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    
    Route::post('/booking/{id}/confirm-presence', [RiwayatController::class, 'confirmPresence'])->name('booking.confirm');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('lapangan', LapanganController::class)->except(['show']);

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::patch('/{booking}/status/{status}', [AdminBookingController::class, 'updateStatus'])->name('update_status');
    });

    Route::get('/jadwal', [JadwalController::class, 'adminIndex'])->name('jadwal.index');
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/riwayat/export', [RiwayatController::class, 'exportExcel'])->name('riwayat.export');
    Route::get('/riwayat', [RiwayatController::class, 'adminIndex'])->name('riwayat.index');
    Route::patch('/riwayat/{id}/status', [RiwayatController::class, 'updateStatus'])->name('riwayat.updateStatus');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    Route::get('/kelolapengguna', [KelolaPenggunaController::class, 'index'])->name('kelolapengguna.index');
});

require __DIR__ . '/auth.php';