<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
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
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
});

Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    Route::get('/colocation', [ColocationController::class, 'index'])->name('colocation.index');
    Route::post('/colocation', [ColocationController::class, 'store'])->name('colocation.store');
});


Route::post('/colocation/invite/{colocation}', [InvitationController::class, 'send'])->name('colocation.invite');
Route::get('/colocation/join/{token}', [InvitationController::class, 'join'])->name('colocation.join');
Route::get('/colocation/register/{token}', [ColocationController::class, 'showRegistrationForm'])->name('colocation.register');
Route::post('/colocation/register/{token}', [ColocationController::class, 'registerUser'])->name('colocation.register.submit');

Route::get('/colocation/accept/{token}', [ColocationController::class, 'accept'])
    ->name('colocation.accept');

Route::get('/colocation/join/{token}', [InvitationController::class, 'join'])
    ->name('colocation.join')
    ->middleware('auth');



    Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    
    // Owner  
    Route::post('/colocation/invite/{colocation}', [InvitationController::class, 'send'])->name('colocation.invite');

    //  Accepter/Refuser 
    Route::get('/invitations/join/{token}', [InvitationController::class, 'join'])->name('colocation.join');

    // Invité
    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitation.accept');

    //cliqui Refuser
    Route::post('/invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitation.refuse');

});


    Route::get('/colocation/{id}', [ColocationController::class, 'show'])
    ->name('index.blade.php');


require __DIR__.'/auth.php';
