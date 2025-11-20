<?php

use App\Http\Controllers\Reports\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('reports')->name('reports.')->group(function () {
    // Main reports page
    Route::get('/', [ReportController::class, 'index'])->name('index');

    // Demographics report
    Route::get('/demographics', [ReportController::class, 'demographics'])->name('demographics');

    // Leadership report
    Route::get('/leadership', [ReportController::class, 'leadership'])->name('leadership');

    // Engagement report
    Route::get('/engagement', [ReportController::class, 'engagement'])->name('engagement');

    // Youth profiles report
    Route::get('/profiles', [ReportController::class, 'profiles'])->name('profiles');

    // Data quality report
    Route::get('/data-quality', [ReportController::class, 'dataQuality'])->name('data-quality');
});
