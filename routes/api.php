<?php

use App\Http\Controllers\Api\V1\ApiPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API v1 Routes — Karang Taruna Kabupaten Bandung
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Endpoints with Rate Limiting (60 requests/minute)
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/news', [ApiPortalController::class, 'news']);
        Route::get('/news/{slug}', [ApiPortalController::class, 'newsDetail']);
        Route::get('/events', [ApiPortalController::class, 'events']);
        Route::get('/programs', [ApiPortalController::class, 'programs']);
        Route::get('/achievements', [ApiPortalController::class, 'achievements']);
        Route::get('/galleries', [ApiPortalController::class, 'galleries']);
        Route::get('/downloads', [ApiPortalController::class, 'downloads']);
        Route::get('/karang-taruna-units', [ApiPortalController::class, 'units']);
        Route::get('/territory', [ApiPortalController::class, 'territory']);
        Route::get('/statistics', [ApiPortalController::class, 'statistics']);

        // Auth Login
        Route::post('/auth/login', [ApiPortalController::class, 'login']);
    });

    // Protected Private Endpoints (Laravel Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/profile', [ApiPortalController::class, 'userProfile']);
        Route::post('/auth/logout', [ApiPortalController::class, 'logout']);
    });
});
