<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodController;

Route::get('/', function () {
    return view('welcome');
});

// All routes inside this group require the user to be logged in.
Route::middleware('auth')->group(function () {
    // The main dashboard for logged-in users
    Route::get('/dashboard', [MoodController::class, 'dashboard'])->name('dashboard');

    // API endpoint to check if a mood exists for a given date
    Route::get('/moods/check-exists', [MoodController::class, 'checkMoodExists'])->name('moods.checkExists');

    // Custom mood routes must be defined BEFORE the resource controller.
    Route::get('/moods/trashed', [MoodController::class, 'trashed'])->name('moods.trashed');
    Route::get('/moods/summary', [MoodController::class, 'summary'])->name('moods.summary');
    Route::post('/moods/restore/{id}', [MoodController::class, 'restore'])->name('moods.restore');
    Route::get('/moods/export/pdf', [MoodController::class, 'exportPdf'])->name('moods.export.pdf');

    // All Moods-related RESTful routes are now protected.
    Route::resource('moods', MoodController::class)->except(['index']);
    Route::get('/moods', [MoodController::class, 'index'])->name('moods.index'); // Explicitly define moods.index


    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// The authentication routes from Breeze are included here and are public.
require __DIR__.'/auth.php';

