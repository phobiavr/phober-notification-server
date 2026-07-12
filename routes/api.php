<?php

use App\Http\Requests\SendMessageRequest;
use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Phobiavr\PhoberLaravelCommon\Clients\AuthClient;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

Route::post('/', function (SendMessageRequest $request) {
    $payload = $request->payload();
    SendMessageJob::dispatch($payload->provider->value, $payload->channel->value, $payload->message);

    return response()->json(['message' => 'Message was scheduled for sending']);
});

Route::prefix('/webhook')->group(function () {
    Route::post('telegram', function (Request $request) {
        $message = $request->get('message');
        $text = $message['text'];
        $chatId = $message['chat']['id'];
        $username = $message['chat']['username'];

        Storage::disk('local')->put('telegram-' . now() . '-' . ($chatId ?? 'N/A') . '-webhook.json', json_encode($request->all(), JSON_PRETTY_PRINT));

        if (!empty($text) && !empty($chatId) && !empty($username) && str_starts_with($text, '/start ')) {
            $encoded = substr($text, 7);
            $decoded = base64_decode(strtr($encoded, '-_', '+/'));

            parse_str($decoded, $params);

            if ($params['server'] === 'auth') {
                if ($params['action'] === 'link') {
                    $payload = [
                        'chat_id' => $chatId,
                        'username' => $username,
                        'user_id' => $params['id'],
                        'token' => $params['token'],
                    ];

                    if (!AuthClient::linkTelegram($payload)) {
                        SendMessageJob::dispatch(
                            NotificationProvider::TELEGRAM->value, 
                            $chatId, 
                            'Something went wrong! Please try again later.'
                        );
                    }
                }
            }
        }

        return 'ok';
    });
});
