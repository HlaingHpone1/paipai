<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrganizationController;
use Illuminate\Support\Facades\Route;


Route::post('v1/auth/login', [AuthController::class, 'login']);

Route::post('v1/auth/logout', [AuthController::class, 'logout']);

Route::post('v1/auth/refresh', [AuthController::class, 'refresh']);

Route::get('/ping', fn() => 'pong');

// Route::group(['middleware' => 'auth:api'], function () {});
