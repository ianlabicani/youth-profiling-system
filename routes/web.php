<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $eventController = new \App\Http\Controllers\Public\EventController;
    $data = $eventController->featured();

    return view('public.welcome', $data);
})->name('welcome');

// Public Events
Route::get('/events', [\App\Http\Controllers\Public\EventController::class, 'index'])->name('public.events.index');

Route::get('/dashboard', function () {

    $user = auth()->user();

    // Redirect based on user role
    if ($user->hasRole('BARANGAY')) {
        return redirect()->route('brgy.dashboard');
    } elseif ($user->hasRole('MUNICIPAL')) {
        return redirect()->route('municipal.dashboard');
    }

    // Default fallback if no specific role
    return view('public.welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/brgy.php';
require __DIR__.'/municipal.php';
