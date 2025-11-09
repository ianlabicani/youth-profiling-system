<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('brgy')->name('brgy.')->group(function () {

    Route::get('/dashboard', function () {
        return view('brgy.dashboard');
    })->name('dashboard');

});
