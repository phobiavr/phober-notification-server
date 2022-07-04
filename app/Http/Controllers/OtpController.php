<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOtpRequest;
use App\Models\Otp;
use Illuminate\Http\Request;
use NotificationChannels\Telegram\Telegram;

class OtpController extends Controller
{
    public function sendOtp() {
      /** @var Otp $otp */
      $otp = Otp::factory()->create();

      app(Telegram::class)->sendMessage([
        'chat_id' => '-1001713564048',
        'text' => 'OTP: ' . $otp->code
      ]);

      return response()->json([
        'message' => 'OTP created successfully',
        'id' => $otp->id,
      ]);
    }

    public function checkOtp(CheckOtpRequest $request) {
      $otp = Otp::query()
        ->where('active', true)
        ->where('code', $request->code)
        ->findOrFail($request->id);

      $otp->active = false;
      $otp->save();

      return response()->json([
        'message' => 'OTP validation success'
      ]);
    }
}
