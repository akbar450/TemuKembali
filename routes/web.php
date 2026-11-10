<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik (Pengunjung) — PRD Section 7
|--------------------------------------------------------------------------
*/
Route::get('/', [ReportController::class, 'home'])->name('home');
Route::get('/laporan', [ReportController::class, 'index'])->name('report.index');
Route::get('/lapor', [ReportController::class, 'create'])->name('report.create');
Route::post('/lapor', [ReportController::class, 'store'])
    ->middleware('throttle:10,60') // Batasan 10 laporan per IP per jam (PRD Section 6)
    ->name('report.store');
Route::get('/laporan/{code}/sukses', [ReportController::class, 'success'])->name('report.success');
Route::get('/laporan/{code}', [ReportController::class, 'show'])->name('report.show');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi Admin — PRD Section 7 & F4
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1') // Proteksi Brute Force: maks 5 percobaan per menit
    ->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Rute Pengelolaan Admin — PRD Section 7 & F5
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/laporan');
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/{code}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::patch('/laporan/{code}/status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::put('/laporan/{code}', [AdminReportController::class, 'update'])->name('reports.update');
    Route::delete('/laporan/{code}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
});
