<?php
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CollectionController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\ProductFilterController;   

Route::prefix('v1')->group(function () {
    Route::get('/home', HomeController::class);

    Route::get('/products/filters', ProductFilterController::class);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

   
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/collections/{slug}', [CollectionController::class, 'show']);
    Route::get('/search', SearchController::class);
    Route::get('/settings', function () {
        return response()->json([
            'settings' => app(\App\Services\SettingService::class)->all(),
        ]);
    });
});




