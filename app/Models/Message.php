<?php

namespace App\Models;

use NotificationChannels\Discord\Discord;
use NotificationChannels\Telegram\Telegram;
use Shared\Notification\Channel;
use Shared\Notification\Provider;

class Message {
    public static function send(string $provider, string $channel, string $message): void {
        if ($provider === Provider::TELEGRAM->value) {
            app(Telegram::class)->sendMessage([
                'chat_id' => self::getChannelId($channel, $provider),
                'text'    => $message
            ]);
        } elseif ($provider === Provider::DISCORD->value) {
            app(Discord::class)->send(
                self::getChannelId($channel, $provider),
                ["content" => $message]);
        }
    }

    private static function getChannelId(string $channel, string $provider) {
        $channelConfig = [
            Provider::TELEGRAM->value => [
                Channel::SUPPORT->value => config('app.telegram_support_channel'),
                Channel::OTP->value     => config('app.telegram_otp_channel'),
            ],
            Provider::DISCORD->value  => [
                Channel::SUPPORT->value => config('app.discord_support_channel'),
                Channel::OTP->value     => config('app.discord_otp_channel'),
            ],
        ];

        return $channelConfig[$provider][$channel] ?? null;
    }
}
