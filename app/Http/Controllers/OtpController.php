<?php

namespace App\Http\Controllers;

use Abdukhaligov\LaravelOTP\OtpFacade as Otp;
use App\Jobs\SendMessageJob;
use App\Models\Channel;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OtpController extends BaseController {
    public function generateOtp(Request $request): JsonResponse {
        $identifier = $request->get('identifier');
        $digits = $request->get('digits');
        $validity = $request->get('validity');

        $token = Otp::generate($identifier, $digits, $validity, onlyDigits: true);
        $message = 'OTP: ' . $token;

        SendMessageJob::dispatch(Provider::TELEGRAM, Channel::OTP, $message);

        return response()->json(['message' => 'OTP generation request accepted']);
    }

    public function validateOtp(Request $request): JsonResponse {
        $identifier = $request->get('identifier');
        $token = strtoupper($request->get('token'));

        $valid = Otp::validate($identifier, $token);

        if (!$valid) {
            return response()->json(['message' => 'OTP is invalid'], 400);
        }

        return response()->json(['message' => 'OTP validated successfully']);
    }

    public function submitOtp(Request $request): JsonResponse {
        $identifier = $request->get('identifier');
        $token = strtoupper($request->get('token'));

        $valid = Otp::submit($identifier, $token);

        if (!$valid) {
            return response()->json(['message' => 'OTP is invalid'], 400);
        }

        return response()->json(['message' => 'OTP submitted successfully']);
    }

    public function checkSubmitted(string $identifier): JsonResponse {
        $valid = Otp::checkSubmitted($identifier);

        if (!$valid) {
            return response()->json(['message' => 'OTP is not submitted'], 400);
        }

        return response()->json(['message' => 'OTP validated successfully']);
    }
}
