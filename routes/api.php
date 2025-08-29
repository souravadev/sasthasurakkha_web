<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\SAAuthController;
use App\Http\Controllers\Api\v1\SAOTPController;

Route::prefix('v1')->group(function() {
    Route::post('/auth/request', [SAAuthController::class, 'execute']);

    Route::middleware('jwt.verify')->group(function() {
        Route::post('/otp/resend', [SAOTPController::class, 'resend']);
        Route::post('/auth/verify', [SAAuthController::class, 'authenticate']);
    });
});
