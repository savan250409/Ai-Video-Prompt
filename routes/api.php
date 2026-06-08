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

// Ngendev Video API — Bearer token required
Route::middleware('ngd.api.auth')->prefix('v1/ngd')->name('api.ngendev.')->group(function () {
    Route::get('getAiVideoCategories',      [NgendevVideoApiController::class, 'getAiVideoCategories'])->name('categories');
    Route::post('getAiVideoByCategoryId',   [NgendevVideoApiController::class, 'getAiVideoByCategoryId'])->name('videosByCategory');
    Route::get('getAllCategoryNames',        [NgendevVideoApiController::class, 'getAllCategoryNames'])->name('categoryNames');
});
