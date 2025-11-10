<?php

use App\Http\Controllers\BRGY\SKCouncilController;
use App\Http\Controllers\BRGY\YouthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('brgy')->name('brgy.')->group(function () {

    Route::get('/dashboard', function () {
        return view('brgy.dashboard');
    })->name('dashboard');

    // Youth Management Routes
    Route::resource('youth', YouthController::class);

    // Youth Heatmap
    Route::get('heatmap', [YouthController::class, 'heatmap'])->name('youth.heatmap');

    // SK Council Management Routes
    Route::resource('sk-councils', SKCouncilController::class);

});
