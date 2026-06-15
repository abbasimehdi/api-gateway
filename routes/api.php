<?php

use App\Http\Controllers\Api\GatewayController;

Route::post('/gateway/users', [GatewayController::class, 'createUser']);
Route::get('/gateway/users', [GatewayController::class, 'getUser']);
