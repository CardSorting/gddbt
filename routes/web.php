<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home/Landing page route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes (Laravel's built-in auth)
Auth::routes();

// Dashboard route (for authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Module routes
    Route::get('/modules', function () {
        return view('modules.index');
    })->name('modules.index');
    
    Route::get('/modules/{module}', function ($module) {
        return view('modules.show', compact('module'));
    })->name('modules.show');
    
    // Daily goals routes
    Route::get('/daily-goals', function () {
        return view('daily-goals.index');
    })->name('daily-goals.index');
    
    Route::get('/daily-goals/create', function () {
        return view('daily-goals.create');
    })->name('daily-goals.create');
});
