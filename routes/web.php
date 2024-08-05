<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [AdminController::class, 'index']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['web','role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/karyawan', 'karyawan')->name('karyawan');
        Route::post('/karyawan-store', 'store_karyawan')->name('karyawan.store');
        Route::post('/karyawan-update', 'update_karyawan')->name('karyawan.update');
        Route::post('/karyawan-delete', 'delete_karyawan')->name('karyawan.delete');
        Route::get('/jadwal', 'jadwal')->name('karyawan.jadwal');
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/riwayat-jadwal', 'history')->name('history');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/deploy', [Controller::class, 'deploy']);

require __DIR__.'/auth.php';
