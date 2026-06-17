<?php

use App\Http\Controllers\Api\GatewayController;
use App\Http\Controllers\Api\Auth\AuthController;

// routes/api.php
Route::any('/gateway/{service}/{path?}', [GatewayController::class, 'proxy'])
    ->where('path', '.*')
    ->middleware('auth:api');

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');