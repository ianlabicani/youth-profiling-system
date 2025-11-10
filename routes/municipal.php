<?php

use App\Http\Controllers\Municipal\AccountController;
use App\Http\Controllers\Municipal\BarangayController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('municipal')->name('municipal.')->group(function () {

    Route::get('/dashboard', function () {
        return view('municipal.dashboard');
    })->name('dashboard');

    // Accounts Management Routes
    Route::resource('accounts', AccountController::class);

    // Barangays Management Routes
    Route::resource('barangays', BarangayController::class);

    // Youths Management Routes (shallow nested under barangays)
    Route::get('barangays/{barangay}/youths', [BarangayController::class, 'youths'])->name('barangays.youths');
    Route::get('barangays/{barangay}/sk-councils', [BarangayController::class, 'skCouncils'])->name('barangays.sk-councils');
});
