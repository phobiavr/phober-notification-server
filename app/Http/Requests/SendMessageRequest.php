<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Phobiavr\PhoberLaravelCommon\Data\SendMessagePayload;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class SendMessageRequest extends FormRequest {
    public function rules(): array {
        return [
            'provider' => ['required', Rule::enum(NotificationProvider::class)],
            'channel'  => ['required', Rule::enum(NotificationChannel::class)],
            'message'  => ['required', 'string'],
        ];
    }

    public function payload(): SendMessagePayload {
        return SendMessagePayload::fromArray($this->validated());
    }
}
