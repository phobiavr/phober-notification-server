<?php

namespace App\Http\Controllers;

use Abdukhaligov\LaravelOTP\OtpFacade as Otp;
use App\Http\Requests\GenerateOtpRequest;
use App\Http\Requests\ValidateOtpRequest;
use App\Jobs\SendMessageJob;
use App\Models\Channel;
use App\Models\Provider;

class OtpController extends Controller
{
  /**
   * @OA\Post(
   *   path="/otp/generate",
   *   summary="Generate OTP",
   *   operationId="generateOtp",
   *   tags={"OTP"},
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(
   *       example={"identifier": "service1-test@gmail.com", "digits": 6, "validity": 10}
   *      )
   *   ),
   *   @OA\Response(
   *     response="200",
   *     description="ok",
   *   )
   * )
   */
  public function generateOtp(GenerateOtpRequest $request)
  {
    $identifier = $request->identifier;
    $digits = $request->digits;
    $validity = $request->validity;

    $token = Otp::generate($identifier, $digits, $validity);
    $message = 'OTP: ' . $token;

    SendMessageJob::dispatch(Provider::TELEGRAM, Channel::OTP, $message);

    return response()->json(['message' => 'OTP generation request accepted']);
  }

  /**
   * @OA\Post(
   *   path="/otp/validate",
   *   summary="Validate OTP",
   *   operationId="validatgeOtp",
   *   tags={"OTP"},
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(
   *       example={"identifier": "service1-test@gmail.com", "token": "123456"}
   *      )
   *   ),
   *   @OA\Response(
   *     response="200",
   *     description="ok",
   *   )
   * )
   */
  public function validateOtp(ValidateOtpRequest $request)
  {
    $identifier = $request->identifier;
    $token = strtoupper($request->token);

    $valid = Otp::validate($identifier, $token);

    if (!$valid) {
      return response()->json(['message' => 'OTP is invalid'], 400);
    }

    return response()->json(['message' => 'OTP validated successfully']);
  }
}
