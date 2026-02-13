<?php

use App\Http\Controllers\BaseController;
use App\Http\Controllers\FundRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [BaseController::class, 'index'])->name('home');
    Route::get('/contact', [BaseController::class, 'contact'])->name('contact');
    Route::get('/conditions', [BaseController::class, 'conditions'])->name('conditions');
    Route::get('/privacy', [BaseController::class, 'privacy'])->name('privacy');
    Route::get('/return_policy', [BaseController::class, 'policyReturn'])->name('return');
    Route::get('/refund_policy', [BaseController::class, 'policyRefund'])->name('refund');
    // Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/payment/success', [FundRequestController::class, 'success'])->name('payment.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/api.php';