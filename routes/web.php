<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GajiController;
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
        Route::get('/card-id-karyawan/{id}', 'view_id_card')->name('karyawan.card-id');
        Route::get('/download-card-id/{id}', 'downloadCardId')->name('karyawan.download.card-id');
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
        Route::post('/import-data-karyawan', 'importDataKaryawan')->name('import.data.karyawan');
        Route::get('/download-template-import', 'downloadTemplateImport')->name('download.template.import');
        Route::post('/import-jadwal-karyawan', 'importJadwalKaryawan')->name('import.jadwal.karyawan');
        Route::get('/download-template-jadwal', 'downloadTemplateJadwal')->name('download.template.jadwal');
        

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::controller(GajiController::class)->group(function(){
        Route::get('/penggajian', 'salary_page')->name('penggajian');
        Route::post('/store-gaji', 'store')->name('gaji.store');
        Route::get('/riwayat-gaji', 'riwayatGaji')->name('gaji.riwayat');
        Route::get('/gaji', 'detailsGaji')->name('gaji.details');
        Route::get('/slip-gaji-view/{id}', 'slipGajiView')->name('gaji.slip-gaji.view');
        Route::get('/frame-slip-gaji/{id}', 'frameSlipGaji')->name('gaji.slip-gaji.frame');
        Route::post('/delete-slip-gaji', 'deleteGaji')->name('gaji.slip-gaji.delete');
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
        Route::get('/forgot-password', [AuthenticatedSessionController::class, 'forgotPassword'])->name('admin.forgot_password');
    });
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/deploy', [Controller::class, 'deploy']);

require __DIR__.'/auth.php';
