<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOtpRequest;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use NotificationChannels\Telegram\Telegram;
use OpenApi\Annotations as OA;

class OtpController extends Controller
{
  /**
   * @OA\Get(
   *   path="/otp",
   *   summary="Send OTP",
   *   operationId="sendOtp",
   *   tags={"OTP"},
   *   security={{"auth.server": {}}},
   *   @OA\Response(
   *     response="200",
   *     description="ok",
   *   )
   * )
   */
  public function sendOtp()
  {
    /** @var Otp|Builder $factoryOtp */
    $factoryOtp = Otp::factory()->make();

    app(Telegram::class)->sendMessage([
      'chat_id' => '-1001713564048',
      'text' => 'OTP: ' . $factoryOtp->clear_code
    ]);

    /** @var Otp|Builder $otp */
    $otp = $factoryOtp->create(array_merge(['code' => $factoryOtp->clear_code], $factoryOtp->attributesToArray()));

    return response()->json([
      'message' => 'OTP created successfully',
      'id' => $otp->id,
    ]);
  }

  /**
   * @OA\Post (
   *   path="/otp",
   *   summary="Check OTP",
   *   operationId="checkOtp",
   *   tags={"OTP"},
   *   security={{"auth.server": {}}},
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(
   *       example={"code":"A1B2C3", "id":"1815ce18-0b4c-4d5f-ac73-a3b4aba36553"}
   *      )
   *   ),
   *   @OA\Response(
   *     response="200",
   *     description="ok",
   *   )
   * )
   */
  public function checkOtp(CheckOtpRequest $request)
  {
    /** @var Otp|Builder $otp */
    $otp = Otp::query()
      ->where('active', true)
      ->findOrFail($request->id);

    if (!Hash::check($request->code, $otp->code)) {
      return response()->json(['message' => 'OTP is invalid']);
    }

    $otp->active = false;
    $otp->save();

    return response()->json([
      'message' => 'OTP validation success'
    ]);
  }
}
