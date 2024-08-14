<?php

use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/', function (Request $request) {
    $provider = $request->provider;
    $channel = $request->channel;
    $message = $request->message;

    SendMessageJob::dispatch($provider, $channel, $message);

    return response()->json(['message' => 'Message was scheduled for sending']);
});
