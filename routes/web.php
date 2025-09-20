<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Incident routes
    Route::resource('incidents', IncidentController::class);
    Route::post('/incidents/{incident}/comments', [IncidentController::class, 'addComment'])->name('incidents.comments');
    
    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::post('/incidents/{incident}/assign', [IncidentController::class, 'assign'])->name('incidents.assign');
        Route::post('/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('incidents.status');
    });
});
