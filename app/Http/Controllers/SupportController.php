<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportMessageRequest;
use Response;

class SupportController extends Controller
{

  /**
   * @OA\Post (
   *   path="/support/message",
   *   summary="Send message to support",
   *   operationId="supportMessage",
   *   tags={"Support"},
   *   security={{"auth.server": {}}},
   *   @OA\Parameter(
   *     name="subject",
   *     in="query",
   *     required=true,
   *     @OA\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="message",
   *     in="query",
   *     required=true,
   *     @OA\Schema(
   *       type="string"
   *     )
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

      return Response::json();
    }
}
