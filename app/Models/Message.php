<?php

namespace App\Models;

use NotificationChannels\Discord\Discord;
use NotificationChannels\Telegram\Telegram;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class Message {
    public static function send(string $provider, string $channel, string $message): void {
        if ($provider === NotificationProvider::TELEGRAM->value) {
            app(Telegram::class)->sendMessage([
                'chat_id' => self::getChannelId($channel, $provider),
                'text'    => $message
            ]);
        } elseif ($provider === NotificationProvider::DISCORD->value) {
            app(Discord::class)->send(
                self::getChannelId($channel, $provider),
                ["content" => $message]);
        }
    }

    private static function getChannelId(string $channel, string $provider) {
        $channelConfig = [
            NotificationProvider::TELEGRAM->value => [
                NotificationChannel::SUPPORT->value => config('app.telegram_support_channel'),
                NotificationChannel::OTP->value     => config('app.telegram_otp_channel'),
            ],
            NotificationProvider::DISCORD->value  => [
                NotificationChannel::SUPPORT->value => config('app.discord_support_channel'),
                NotificationChannel::OTP->value     => config('app.discord_otp_channel'),
            ],
        ];

        return $channelConfig[$provider][$channel] ?? null;
    }
}
