<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\LapanganController; 
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminBookingController; 

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


Route::middleware('auth')->group(function () {
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('lapangan', LapanganController::class)->except(['show']);
        
        Route::prefix('booking')->name('booking.')->group(function () {
             Route::get('/', [AdminBookingController::class, 'index'])->name('index'); 
             Route::patch('/{booking}/status/{status}', [AdminBookingController::class, 'updateStatus'])->name('update_status'); 
        });

        Route::get('/jadwal', [JadwalController::class, 'adminIndex'])->name('jadwal.index');
        Route::get('/riwayat', [RiwayatController::class, 'adminIndex'])->name('riwayat.index');

        Route::get('/users', function () {
            return view('admin.users.index');
        })->name('users.index');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index'); 

    Route::get('/booking/{lapangan:id}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{lapangan:id}/store', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

});

require __DIR__ . '/auth.php';