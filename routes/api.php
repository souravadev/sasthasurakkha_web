<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\SAAuthController;

Route::prefix('v1')->group(function() {
    Route::post('/auth', [SAAuthController::class, 'execute']);
});
