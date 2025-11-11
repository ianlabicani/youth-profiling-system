<?php

use App\Http\Controllers\BRGY\BarangayEventController;
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
    Route::get('sk-councils/search/youth', [SKCouncilController::class, 'searchYouth'])->name('sk-councils.search-youth');
    Route::post('sk-councils/{skCouncil}/activate', [SKCouncilController::class, 'activate'])->name('sk-councils.activate');

    // Barangay Events Management Routes
    Route::resource('events', BarangayEventController::class);

});
