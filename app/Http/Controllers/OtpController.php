<?php

namespace App\Http\Controllers;

use Abdukhaligov\LaravelOTP\OtpFacade as Otp;
use App\Http\Requests\GenerateOtpRequest;
use App\Http\Requests\ValidateOtpRequest;
use App\Jobs\SendMessageJob;
use App\Models\Channel;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;

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
  public function generateOtp(GenerateOtpRequest $request): JsonResponse
  {
    $identifier = $request->identifier;
    $digits = $request->digits;
    $validity = $request->validity;

    $token = \Otp::generate($identifier, $digits, $validity, onlyDigits: true);
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
  public function validateOtp(ValidateOtpRequest $request): JsonResponse
  {
    $identifier = $request->identifier;
    $token = strtoupper($request->token);

    $valid = app('otp')::validate($identifier, $token);

    if (!$valid) {
      return response()->json(['message' => 'OTP is invalid'], 400);
    }

    return response()->json(['message' => 'OTP validated successfully']);
  }

  public function submitOtp(ValidateOtpRequest $request): JsonResponse
  {
    $identifier = $request->identifier;
    $token = strtoupper($request->token);

    $valid = Otp::submit($identifier, $token);

    if (!$valid) {
      return response()->json(['message' => 'OTP is invalid'], 400);
    }

    return response()->json(['message' => 'OTP submitted successfully']);
  }

  public function checkSubmitted(string $identifier): JsonResponse
  {
    $valid = Otp::checkSubmitted($identifier);

    if (!$valid) {
      return response()->json(['message' => 'OTP is not submitted'], 400);
    }

    return response()->json(['message' => 'OTP validated successfully']);
  }
}
