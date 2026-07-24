<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomingPayloadController;

Route::middleware(['auth:sanctum', 'throttle:vendor-api', App\Http\Middleware\VerifyVendorIp::class])
    ->group(function () {
        Route::post('/coal-data/inbound', [IncomingPayloadController::class, 'store']);
    });
