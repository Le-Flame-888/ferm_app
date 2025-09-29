<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipementController;

Route::get('/', function () {
    return redirect()->route('equipements.index');
});

// Authentication Routes
Auth::routes();

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Resource Routes for Equipements
    Route::resource('equipements', EquipementController::class);
    
    // Additional custom routes can be added here
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
