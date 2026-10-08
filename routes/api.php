<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationApiController;

Route::get('/notifications', [NotificationApiController::class, 'index']);
Route::get('/notifications/{id}', [NotificationApiController::class, 'show']);
Route::post('/device-token', [NotificationApiController::class, 'storeDeviceToken']);
Route::post('/token/update', [NotificationApiController::class, 'updateDeviceToken']);
Route::get('/app/version', [NotificationApiController::class, 'getAppVersion']);
