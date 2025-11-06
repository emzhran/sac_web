<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FutsalController;
use App\Http\Controllers\BadmintonController;
use App\Http\Controllers\VoliController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLapanganController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('lapangan')->name('lapangan.')->group(function () {
            Route::get('/futsal', [AdminLapanganController::class, 'futsalIndex'])->name('futsal');
            Route::patch('/{type}/{id}/status/{status}', [AdminLapanganController::class, 'updateStatus'])->name('update_status');
            Route::get('/badminton', function () {
                return view('admin.lapangan.badminton');
            })->name('badminton');
            Route::get('/voli', function () {
                return view('admin.lapangan.voli');
            })->name('voli');
            Route::get('/basket', function () {
                return view('admin.lapangan.basket');
            })->name('basket');
            Route::get('/create', function () {
                return view('admin.lapangan.create');
            })->name('create');
        });

        Route::get('/jadwal', function () {
            return view('admin.jadwal.index');
        })->name('jadwal.index');

        Route::get('/riwayat', function () {
            return view('admin.riwayat.index');
        })->name('riwayat.index');

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

    Route::get('/booking', function () {
        return view('booking.booking');
    })->name('booking.index');

    Route::get('/futsal', function () {
        return view('lapangan.futsal');
    })->name('futsal');
    Route::post('/futsal/store', [FutsalController::class, 'store'])->name('futsal.store');

    Route::get('/badminton', function () {
        return view('lapangan.badminton');
    })->name('badminton');
    Route::post('/badminton/store', [BadmintonController::class, 'store'])->name('badminton.store');

    Route::get('/voli', function () {
        return view('lapangan.voli');
    })->name('voli');
    Route::post('/voli/store', [VoliController::class, 'store'])->name('voli.store');

    Route::get('/basket', function () {
        return view('lapangan.basket');
    })->name('basket');
    Route::post('/basket/store', [BasketController::class, 'store'])->name('basket.store');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat/store', [RiwayatController::class, 'store'])->name('riwayat.store');
});

require __DIR__ . '/auth.php';
