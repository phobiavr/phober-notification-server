<?php

namespace App\Models;

use NotificationChannels\Discord\Discord;
use NotificationChannels\Telegram\Telegram;

class Message {
    public static function send(Provider $provider, Channel $channel, string $message): void {
        if ($provider === Provider::TELEGRAM) {
            app(Telegram::class)->sendMessage([
                'chat_id' => self::getChannelId($channel, $provider),
                'text'    => $message
            ]);
        } elseif ($provider === Provider::DISCORD) {
            app(Discord::class)->send(
                self::getChannelId($channel, $provider),
                ["content" => $message]);
        }
    }

    private static function getChannelId(Channel $channel, Provider $provider) {
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

        $providerKey = $provider->value;
        $channelKey = $channel->value;

        return $channelConfig[$providerKey][$channelKey] ?? null;
    }
}
