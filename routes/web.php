<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleAuthController;
use App\Http\Controllers\DinasController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\DeveloperPerumahanController;
use App\Http\Controllers\DinasVerifikasiController;
use App\Http\Controllers\DeveloperPermohonanController;
use App\Http\Controllers\DeveloperDashboardController;
use App\Http\Controllers\DinasPermohonanController;
use App\Http\Controllers\DeveloperNotificationController;
use App\Http\Controllers\DeveloperSettingsController;
use App\Http\Controllers\DinasSettingsController;

// ✅ RTH Controller (kamu buat)
use App\Http\Controllers\DeveloperRthController;
// use App\Http\Controllers\DinasRthController; // belum dipakai dulu

/*
|--------------------------------------------------------------------------
| Public / Landing
|--------------------------------------------------------------------------
*/
Route::view('/', 'landing')->name('landing');

Route::get('/dashboard-masyarakat', [MasyarakatController::class, 'showDashboard'])
    ->name('home');

Route::get('/masyarakat/dashboard', [MasyarakatController::class, 'showDashboard'])
    ->name('masyarakat.dashboard');

Route::get('/perumahan', [MasyarakatController::class, 'listPerumahan'])
    ->name('perumahan.index');

Route::get('/perumahan/{id}', [MasyarakatController::class, 'showPerumahan'])
    ->name('perumahan.show');

Route::view('/tentang-kami', 'masyarakat.tentang')->name('about');

Route::match(['GET', 'POST'], '/masyarakat/cari-perumahan', [MasyarakatController::class, 'cariPerumahan'])
    ->name('masyarakat.cari');

/*
|--------------------------------------------------------------------------
| Auth Developer
|--------------------------------------------------------------------------
*/
Route::get('/developer/login',    [RoleAuthController::class, 'showDeveloperLogin'])->name('developer.login');
Route::post('/developer/login',   [RoleAuthController::class, 'developerLogin'])->name('developer.login.attempt');

Route::get('/developer/register', [RoleAuthController::class, 'showDeveloperRegister'])->name('developer.register');
Route::post('/developer/register',[RoleAuthController::class, 'registerDeveloper'])->name('developer.register.attempt');

/*
|--------------------------------------------------------------------------
| Developer Area (wajib login)
|--------------------------------------------------------------------------
*/
Route::prefix('developer')
    ->middleware('auth') // ✅ HAPUS role:developer
    ->name('developer.')
    ->group(function () {

    Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])
        ->name('dashboard');

    // Data Perumahan Saya
    Route::get('/perumahan',        [DeveloperPerumahanController::class, 'index'])->name('perumahan.index');
    Route::get('/perumahan/create', [DeveloperPerumahanController::class, 'create'])->name('perumahan.create');
    Route::post('/perumahan',       [DeveloperPerumahanController::class, 'store'])->name('perumahan.store');

    Route::get('/perumahan/{perumahan}', [DeveloperPerumahanController::class, 'show'])
        ->name('perumahan.show');

    Route::get('/perumahan/{perumahan}/edit', [DeveloperPerumahanController::class, 'edit'])
        ->name('perumahan.edit');

    Route::put('/perumahan/{perumahan}', [DeveloperPerumahanController::class, 'update'])
        ->name('perumahan.update');

    // Notifikasi & Revisi
    Route::get('/notifikasi', [DeveloperNotificationController::class, 'index'])
        ->name('notifikasi.index');

    // Permohonan ke Dinas
    Route::get('/permohonan', [DeveloperPermohonanController::class, 'index'])
        ->name('permohonan.index');

    Route::get('/permohonan/nota-dinas/create', [DeveloperPermohonanController::class, 'createNotaDinas'])
        ->name('permohonan.nota.create');

    Route::post('/permohonan/nota-dinas', [DeveloperPermohonanController::class, 'storeNotaDinas'])
        ->name('permohonan.nota.store');

    Route::get('/permohonan/nota-dinas/{nota}', [DeveloperPermohonanController::class, 'showNotaDinas'])
        ->name('permohonan.nota.show');

    Route::get('/permohonan/nota-dinas/{nota}/edit', [DeveloperPermohonanController::class, 'editNotaDinas'])
        ->name('permohonan.nota.edit');

    Route::put('/permohonan/nota-dinas/{nota}', [DeveloperPermohonanController::class, 'updateNotaDinas'])
        ->name('permohonan.nota.update');

    // Pengaturan akun developer
    Route::get('/settings', [DeveloperSettingsController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings', [DeveloperSettingsController::class, 'update'])
        ->name('settings.update');

    // ✅ RTH - Penyiraman Otomatis (monitoring + control)
    Route::get('/rth', [DeveloperRthController::class, 'index'])
        ->name('rth.index');

    Route::post('/rth/pump', [DeveloperRthController::class, 'setPump'])
        ->name('rth.pump');
});

/*
|--------------------------------------------------------------------------
| Auth Dinas
|--------------------------------------------------------------------------
*/
Route::get('/dinas/login',  [RoleAuthController::class, 'showDinasLogin'])->name('dinas.login');
Route::post('/dinas/login', [RoleAuthController::class, 'dinasLogin'])->name('dinas.login.attempt');

Route::get('/dinas/register',  [RoleAuthController::class, 'showDinasRegister'])->name('dinas.register');
Route::post('/dinas/register', [RoleAuthController::class, 'registerDinas'])->name('dinas.register.attempt');

/*
|--------------------------------------------------------------------------
| Dinas Area (wajib login)
|--------------------------------------------------------------------------
*/
Route::prefix('dinas')
    ->middleware('auth') // ✅ HAPUS role:dinas
    ->name('dinas.')
    ->group(function () {

    Route::get('/dashboard', [DinasController::class, 'index'])->name('dashboard');

    // Verifikasi perumahan
    Route::get('/perumahan/verifikasi', [DinasVerifikasiController::class, 'index'])
        ->name('perumahan.verify.index');

    Route::get('/perumahan/verifikasi/{id}', [DinasVerifikasiController::class, 'show'])
        ->name('perumahan.verify.show');

    Route::post('/perumahan/{id}/approve', [DinasVerifikasiController::class, 'approve'])
        ->name('perumahan.approve');

    Route::post('/perumahan/{id}/reject', [DinasVerifikasiController::class, 'reject'])
        ->name('perumahan.reject');

    // Permohonan Nota Dinas masuk
    Route::get('/permohonan/nota-dinas', [DinasPermohonanController::class, 'index'])
        ->name('permohonan.nota.index');

    Route::get('/permohonan/nota-dinas/inventaris', [DinasPermohonanController::class, 'inventaris'])
        ->name('permohonan.nota.inventaris');

    Route::get('/permohonan/nota-dinas/{id}', [DinasPermohonanController::class, 'show'])
        ->name('permohonan.nota.show');

    Route::post('/permohonan/nota-dinas/{id}/approve', [DinasPermohonanController::class, 'approve'])
        ->name('permohonan.nota.approve');

    Route::post('/permohonan/nota-dinas/{id}/revisi', [DinasPermohonanController::class, 'revisi'])
        ->name('permohonan.nota.revisi');

    // Pengaturan akun dinas
    Route::get('/settings', [DinasSettingsController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings', [DinasSettingsController::class, 'update'])
        ->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [RoleAuthController::class, 'logout'])->name('logout');