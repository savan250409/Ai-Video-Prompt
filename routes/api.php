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
Route::get('/test-api', function (Request $request) {
    try {
        // You can add your actual logic here
        $isSuccess = true; // Use this variable to simulate success or failure

        if ($isSuccess) {
            // Success response with status code 200
            return response()->json([
                'status' => true,
                'message' => 'API executed successfully!'
            ], 200);
        } else {
            // Failure response with status code 400 (Bad Request) or other appropriate code
            return response()->json([
                'status' => false,
                'message' => 'API failed. Specific condition not met.'
            ], 400);
        }

    } catch (\Exception $e) {
        // Exception/server error response with status code 500
        return response()->json([
            'status' => false,
            'message' => 'An unexpected server error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
});
