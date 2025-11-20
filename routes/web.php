<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KycController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/payment-management', [DashboardController::class, 'paymentmanagement'])->name('payment.management');
    Route::get('/interact-transactions', [DashboardController::class, 'interacttransactions'])->name('interact.transactions');
    Route::get('/eft-transactions', [DashboardController::class, 'efttransactions'])->name('eft.transactions');
    Route::get('/ftd-transactions', [DashboardController::class, 'ftdtransactions'])->name('ftd.transactions');
    Route::get('/user-management', [DashboardController::class, 'usermanagement'])->name('user.management');
    Route::get('/brands', [DashboardController::class, 'brands'])->name('brands');
    Route::get('/referrers', [DashboardController::class, 'referrers'])->name('referrers');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::get('/withdrawals', [DashboardController::class, 'withdrawals'])->name('withdrawals');
    Route::get('/refwithdrawals', [DashboardController::class, 'refwithdrawals'])->name('refwithdrawals');

    Route::post('/settings', [UserController::class, 'update'])->name('settings.update');
    Route::get('/chart-data', [DashboardController::class, 'getChartData']);
    Route::post('/kyc/submit', [UserController::class, 'submit'])->name('submit.kyc');

     
    Route::post('/pop/submit', [UserController::class, 'submitPop'])->name('submit.pop');

    Route::post('/kyc-tmp-upload', [UserController::class, 'kycTmpUpload']);
    Route::delete('/kyc-tmp-delete', [UserController::class, 'kycTmpDelete']);

    Route::post('/pop-tmp-upload', [UserController::class, 'popTmpUpload']);
    Route::delete('/pop-tmp-delete', [UserController::class, 'popTmpDelete']);

    Route::get('/kyc-management', [DashboardController::class,'kyc'])->name('kyc');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/brands/{id}/edit', [DashboardController::class, 'editbrand'])->name('brands.edit');
    Route::get('/referrers/{id}/edit', [DashboardController::class, 'editreferrer'])->name('referrers.edit');
    Route::get('/user-management/{id}/edit', [DashboardController::class, 'edituser'])->name('user.edit');
});
});

Route::get('/phpinfo', function () {
    phpinfo();
});

require __DIR__.'/auth.php';
