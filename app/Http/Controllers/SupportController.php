<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportMessageRequest;
use App\Jobs\SendMessageJob;
use App\Models\Channel;
use App\Models\Provider;

class SupportController extends Controller
{

  /**
   * @OA\Post (
   *   path="/support/message",
   *   summary="Send message to support",
   *   operationId="supportMessage",
   *   tags={"Support"},
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(
   *       example={"subject":"Subject","message":"Message"}
   *      )
   *   ),
   *   @OA\Response(
   *     response="200",
   *     description="ok",
   *   )
   * )
   */
  public function message(SupportMessageRequest $request)
  {
    $message = "Subject: " . $request->subject . " \nMessage: " . $request->message;

    SendMessageJob::dispatch(Provider::DISCORD, Channel::SUPPORT, $message);

    return response()->json(['message' => 'Message was scheduled for sending']);
  }
}
