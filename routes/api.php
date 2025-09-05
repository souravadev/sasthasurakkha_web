<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\SAAuthController;
use App\Http\Controllers\Api\v1\Home\SAHomeControllerFree;
use App\Http\Controllers\Api\v1\Auth\SAOTPController;

Route::prefix('v1')->group(function() {
    Route::post('/auth/request', [SAAuthController::class, 'execute']);

    Route::middleware('jwt.verify')->group(function() {
        Route::post('/otp/resend', [SAOTPController::class, 'resend']);
        Route::post('/auth/verify', [SAAuthController::class, 'authenticate']);
    });

    Route::get('/home', [SAHomeControllerFree::class, 'execute']);
});
