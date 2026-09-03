<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\SopController;
use App\Http\Controllers\Api\V1\ScheduleController;

/*
|--------------------------------------------------------------------------
| DCMS Read-Only REST API — Version 1
|--------------------------------------------------------------------------
|
| All routes here are GET-only (read-only).
| Base URL: /api/v1
|
| Authentication: This group is intentionally left open (no auth middleware)
| for internal or public read-access use cases. To add Sanctum token auth,
| wrap the group with: ->middleware('auth:sanctum')
|
| Endpoints:
|   GET /api/v1/documents          — list documents (paginated, filterable)
|   GET /api/v1/documents/{id}     — single document detail
|   GET /api/v1/sops               — list SOPs (paginated, filterable)
|   GET /api/v1/sops/{id}          — single SOP with procedures & revision history
|   GET /api/v1/schedules          — list meeting schedules (filterable)
|   GET /api/v1/schedules/{id}     — single meeting with participants & attachments
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    /*
    |------------------------------------------------------------------
    | Module 1: Documents
    | GET /api/v1/documents
    | GET /api/v1/documents/{id}
    |------------------------------------------------------------------
    */
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/{id}', [DocumentController::class, 'show'])
            ->whereNumber('id')
            ->name('show');
    });

    /*
    |------------------------------------------------------------------
    | Module 2: SOPs (Standard Operating Procedures)
    | GET /api/v1/sops
    | GET /api/v1/sops/{id}
    |------------------------------------------------------------------
    */
    Route::prefix('sops')->name('sops.')->group(function () {
        Route::get('/', [SopController::class, 'index'])->name('index');
        Route::get('/{id}', [SopController::class, 'show'])
            ->whereNumber('id')
            ->name('show');
    });

    /*
    |------------------------------------------------------------------
    | Module 3: Meeting Schedules
    | GET /api/v1/schedules
    | GET /api/v1/schedules/{id}
    |------------------------------------------------------------------
    */
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/{id}', [ScheduleController::class, 'show'])
            ->whereNumber('id')
            ->name('show');
    });

});
