<?php

use App\Http\Controllers\Municipal\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('municipal')->name('municipal.')->group(function () {

    Route::get('/dashboard', function () {
        return view('municipal.dashboard');
    })->name('dashboard');

    // Accounts Management Routes
    Route::resource('accounts', AccountController::class);

});
