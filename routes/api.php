<?php

use App\Http\Controllers\Api\V1\ApiManagementController;
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

        // Auth Login (Mobile / CMS Android Login)
        Route::post('/auth/login', [ApiPortalController::class, 'login']);
    });

    // Protected Private Endpoints (Laravel Sanctum - Android CMS Management)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth User Profile & Logout
        Route::get('/user/profile', [ApiPortalController::class, 'userProfile']);
        Route::post('/auth/logout', [ApiPortalController::class, 'logout']);

        // 1. Management: Berita & Artikel
        Route::prefix('manage/articles')->group(function () {
            Route::get('/', [ApiManagementController::class, 'articlesIndex']);
            Route::post('/', [ApiManagementController::class, 'articlesStore']);
            Route::get('/{id}', [ApiManagementController::class, 'articlesShow']);
            Route::post('/{id}', [ApiManagementController::class, 'articlesUpdate']); // POST method allows multipart image update
            Route::put('/{id}', [ApiManagementController::class, 'articlesUpdate']);
            Route::delete('/{id}', [ApiManagementController::class, 'articlesDestroy']);
        });

        // 2. Management: Agenda & Events
        Route::prefix('manage/events')->group(function () {
            Route::get('/', [ApiManagementController::class, 'eventsIndex']);
            Route::post('/', [ApiManagementController::class, 'eventsStore']);
            Route::post('/{id}', [ApiManagementController::class, 'eventsUpdate']);
            Route::put('/{id}', [ApiManagementController::class, 'eventsUpdate']);
            Route::delete('/{id}', [ApiManagementController::class, 'eventsDestroy']);
        });

        // 3. Management: Data Usulan Warga PPKS
        Route::prefix('manage/ppks')->group(function () {
            Route::get('/', [ApiManagementController::class, 'ppksIndex']);
            Route::post('/', [ApiManagementController::class, 'ppksStore']);
            Route::put('/{id}', [ApiManagementController::class, 'ppksUpdate']);
            Route::delete('/{id}', [ApiManagementController::class, 'ppksDestroy']);
        });

        // 4. Management: Struktur Pengurus Unit
        Route::prefix('manage/members')->group(function () {
            Route::get('/', [ApiManagementController::class, 'membersIndex']);
            Route::post('/', [ApiManagementController::class, 'membersStore']);
            Route::post('/{id}', [ApiManagementController::class, 'membersUpdate']);
            Route::put('/{id}', [ApiManagementController::class, 'membersUpdate']);
            Route::delete('/{id}', [ApiManagementController::class, 'membersDestroy']);
        });

        // 5. Management: Profil Unit Sendiri
        Route::prefix('manage/unit')->group(function () {
            Route::get('/me', [ApiManagementController::class, 'myUnit']);
            Route::post('/me', [ApiManagementController::class, 'updateMyUnit']);
            Route::put('/me', [ApiManagementController::class, 'updateMyUnit']);
        });
    });
});
