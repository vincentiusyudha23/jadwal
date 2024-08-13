<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::middleware(['web','role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/karyawan', 'karyawan')->name('karyawan');
        Route::post('/karyawan-store', 'store_karyawan')->name('karyawan.store');
        Route::post('/karyawan-update', 'update_karyawan')->name('karyawan.update');
        Route::post('/karyawan-delete', 'delete_karyawan')->name('karyawan.delete');
        Route::get('/jadwal', 'jadwal')->name('karyawan.jadwal');
        Route::post('/jadwal-store', 'store_jadwal')->name('karyawan.jadwal.store');
        Route::post('/jadwal-update', 'update_jadwal')->name('karyawan.jadwal.update');
        Route::post('/jadwal-delete', 'delete_jadwal')->name('karyawan.jadwal.delete');
        Route::get('/jadwal/{id}', 'show_jadwal')->name('karyawan.jadwal.show');
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/update-password', 'updatePassword')->name('profile.update.password');
        Route::get('/riwayat-jadwal', 'history')->name('history');
        Route::get('/riwayat-jadwal/{tanggal}', 'getHistory')->name('history.show');
        Route::get('/export-jadwal/{tanggal}', 'export_jadwal')->name('export.jadwal');
        Route::get('/export-jadwal-all', 'export_jadwal_all')->name('export.jadwal.all');
        Route::get('/export-akun', 'export_akun_karyawan')->name('export.akun.all');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

Route::middleware(['web','role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function(){
    Route::controller(KaryawanController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/ubah-password', 'profile')->name('profile');
        Route::put('/update-password', 'updatePassword')->name('profile.update.password');
        Route::get('/show-jadwal/{id}', 'show_jadwal')->name('jadwal.show');
        Route::post('/upload-image', 'uploaderImage')->name('upload.image');
        Route::post('/update-jadwal', 'update_jadwal')->name('jadwal.update');
        Route::get('/riwayat-jadwal', 'riwayat_jadwal')->name('jadwal.riwayat');
        Route::get('/export-jadwal', 'export_jadwal')->name('export.jadwal');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

Route::middleware('guest')->group(function(){

    Route::get('/', [AuthenticatedSessionController::class, 'login_karyawan'])->name('firstpage_karyawan');
    Route::post('/login', [AuthenticatedSessionController::class, 'storeKaryawan'])->name('login.karyawan');

    Route::prefix('admin')->group(function(){
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('first_page');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
    });
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/deploy', [Controller::class, 'deploy']);

require __DIR__.'/auth.php';
