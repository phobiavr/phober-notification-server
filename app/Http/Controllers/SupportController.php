<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessageJob;
use App\Models\Channel;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SupportController extends BaseController {
    public function message(Request $request): JsonResponse {
        $message = "Subject: " . e($request->get('subject')) .
            " \nMessage: " . e($request->get('message'));

        SendMessageJob::dispatch(Provider::DISCORD, Channel::SUPPORT, $message);

        return response()->json(['message' => 'Message was scheduled for sending']);
    }
}
