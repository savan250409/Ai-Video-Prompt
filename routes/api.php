<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NgendevVideoApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ngendev Video API — public, no auth required
Route::prefix('ngendev')->name('api.ngendev.')->group(function () {
    Route::get('categories',          [NgendevVideoApiController::class, 'getAiVideoCategories'])->name('categories');
    Route::post('videos-by-category', [NgendevVideoApiController::class, 'getAiVideoByCategoryId'])->name('videosByCategory');
    Route::get('category-names',      [NgendevVideoApiController::class, 'getAllCategoryNames'])->name('categoryNames');
});
