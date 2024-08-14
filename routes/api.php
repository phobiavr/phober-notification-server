<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/support')->group(function () {
    Route::post('/message', [\App\Http\Controllers\SupportController::class, 'message']);
});
