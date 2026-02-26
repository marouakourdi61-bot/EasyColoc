<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;

use App\Http\Controllers\ExpenseController;



Route::get('/', function () {
    return view('welcome');
});






// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', \App\Http\Middleware\CheckBanned::class])
  ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    Route::get('/colocation', [ColocationController::class, 'index'])->name('colocation.index');
    Route::post('/colocation', [ColocationController::class, 'store'])->name('colocation.store');
});





require __DIR__.'/auth.php';
