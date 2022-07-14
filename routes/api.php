<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth.server')->get('/', function () {
  return Auth::guard('server')->user();
});

Route::prefix('/support')->group(function () {
  Route::middleware('auth.server')->group(function () {
    Route::post('/message', [\App\Http\Controllers\SupportController::class, 'message']);
  });
});

Route::prefix('/otp')->group(function () {
  Route::post('/generate', [\App\Http\Controllers\OtpController::class, 'generateOtp']);
  Route::post('/validate', [\App\Http\Controllers\OtpController::class, 'validateOtp']);
});
