<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportMessageRequest;

class SupportController extends Controller
{

  /**
   * @OA\Post (
   *   path="/support/message",
   *   summary="Send message to support",
   *   operationId="supportMessage",
   *   tags={"Support"},
   *   security={{"auth.server": {}}},
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
    public function message(SupportMessageRequest $request) {
      $message = [
        "content" => "Subject: " . $request->subject. " \nMessage: " . $request->message,
      ];

      app(\NotificationChannels\Discord\Discord::class)->send('648319935961104434', $message);

      return response()->json(['message' => 'Message was sent']);
    }
}
