<?php

use App\Http\Controllers\BRGY\BarangayEventController;
use App\Http\Controllers\BRGY\SKCouncilController;
use App\Http\Controllers\BRGY\YouthController;
use App\Http\Controllers\BRGY\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('brgy')->name('brgy.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

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

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BRGY\ReportController::class, 'index'])->name('index');
        Route::get('/demographics', [\App\Http\Controllers\BRGY\ReportController::class, 'demographics'])->name('demographics');
        Route::get('/leadership', [\App\Http\Controllers\BRGY\ReportController::class, 'leadership'])->name('leadership');
        Route::get('/engagement', [\App\Http\Controllers\BRGY\ReportController::class, 'engagement'])->name('engagement');
        Route::get('/profiles', [\App\Http\Controllers\BRGY\ReportController::class, 'profiles'])->name('profiles');
        Route::get('/data-quality', [\App\Http\Controllers\BRGY\ReportController::class, 'dataQuality'])->name('data-quality');
    });

});
