<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/support')->group(function () {
    Route::post('/message', [\App\Http\Controllers\SupportController::class, 'message']);
});

Route::prefix('/otp')->group(function () {
    Route::post('/generate', [\App\Http\Controllers\OtpController::class, 'generateOtp']);
    Route::post('/validate', [\App\Http\Controllers\OtpController::class, 'validateOtp']);
    Route::get('/submit', [\App\Http\Controllers\OtpController::class, 'submitOtp']);
    Route::get('/check-submitted/{identifier}', [\App\Http\Controllers\OtpController::class, 'checkSubmitted']);
});
