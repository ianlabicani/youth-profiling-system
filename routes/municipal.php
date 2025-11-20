<?php

use App\Http\Controllers\Municipal\AccountController;
use App\Http\Controllers\Municipal\BarangayController;
use App\Http\Controllers\Municipal\DashboardController;
use App\Http\Controllers\Municipal\OrganizationController;
use App\Http\Controllers\Municipal\YouthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('municipal')->name('municipal.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/heatmap', [DashboardController::class, 'heatmap'])->name('heatmap');

    // Accounts Management Routes
    Route::resource('accounts', AccountController::class);

    // Barangays Management Routes
    Route::resource('barangays', BarangayController::class);
    Route::get('youths/out-of-school', [YouthController::class, 'outOfSchool'])->name('youths.out-of-school');
    Route::resource('youths', YouthController::class)->only(['index', 'show']);

    // Organizations CRUD - custom routes first (before resource to avoid conflict)
    Route::get('organizations/search-youth', [OrganizationController::class, 'searchYouth'])->name('organizations.search-youth');
    Route::get('organizations/get-youth', [OrganizationController::class, 'getYouth'])->name('organizations.get-youth');
    Route::resource('organizations', OrganizationController::class);

    // Youths Management Routes (shallow nested under barangays)
    Route::get('barangays/{barangay}/youths', [BarangayController::class, 'youths'])->name('barangays.youths');
    Route::get('barangays/{barangay}/youths/{youth}', [BarangayController::class, 'youthShow'])->name('barangays.youths.show');
    Route::get('barangays/{barangay}/sk-councils', [BarangayController::class, 'skCouncils'])->name('barangays.sk-councils.index');
    Route::get('barangays/{barangay}/sk-councils/{skCouncil}', [BarangayController::class, 'skCouncilShow'])->name('barangays.sk-councils.show');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Municipal\ReportController::class, 'index'])->name('index');
        Route::get('/demographics', [\App\Http\Controllers\Municipal\ReportController::class, 'demographics'])->name('demographics');
        Route::get('/leadership', [\App\Http\Controllers\Municipal\ReportController::class, 'leadership'])->name('leadership');
        Route::get('/engagement', [\App\Http\Controllers\Municipal\ReportController::class, 'engagement'])->name('engagement');
        Route::get('/profiles', [\App\Http\Controllers\Municipal\ReportController::class, 'profiles'])->name('profiles');
        Route::get('/data-quality', [\App\Http\Controllers\Municipal\ReportController::class, 'dataQuality'])->name('data-quality');
    });
});
