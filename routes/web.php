<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NgendevVideoCategoryController;
use App\Http\Controllers\NgendevVideoController;

/*
|--------------------------------------------------------------------------
| Web Routes — Admin only, no registration, no password reset
|--------------------------------------------------------------------------
*/

// Root → dashboard if logged in, else login
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Login only (no register, no password reset)
Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
Route::post('login', [LoginController::class, 'login'])->middleware('guest:admin');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected admin dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Legacy /home redirect
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// ── Ngendev Video Category Management ──────────────────────────────────────
Route::middleware('auth:admin')->prefix('ngendev')->name('ngendev.')->group(function () {

    // Categories
    Route::get('categories/reindex', [NgendevVideoCategoryController::class, 'reindex'])->name('categories.reindex');
    Route::post('categories/update-type',   [NgendevVideoCategoryController::class, 'updateType'])->name('categories.updateType');
    Route::post('categories/update-status', [NgendevVideoCategoryController::class, 'updateStatus'])->name('categories.updateStatus');
    Route::resource('categories', NgendevVideoCategoryController::class);

    // Videos
    Route::get('videos/reindex', [NgendevVideoController::class, 'reindex'])->name('videos.reindex');
    Route::post('videos/update-name-change', [NgendevVideoController::class, 'updateNameChange'])->name('videos.updateNameChange');
    Route::resource('videos', NgendevVideoController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // API List
    Route::get('api-list', function () {
        return view('ngendev.api_list.index');
    })->name('api.list');
});
