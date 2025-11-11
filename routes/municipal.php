<?php

use App\Http\Controllers\Municipal\AccountController;
use App\Http\Controllers\Municipal\BarangayController;
use App\Http\Controllers\Municipal\OrganizationController;
use App\Http\Controllers\Municipal\YouthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('municipal')->name('municipal.')->group(function () {

    Route::get('/dashboard', function () {
        return view('municipal.dashboard');
    })->name('dashboard');

    // Accounts Management Routes
    Route::resource('accounts', AccountController::class);

    // Barangays Management Routes
    Route::resource('barangays', BarangayController::class);
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
});
