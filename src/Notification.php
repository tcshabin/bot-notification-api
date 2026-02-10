<?php

namespace NotificationBot;

use NotificationBot\Telegram\TelegramNotifier;
use NotificationBot\Matrix\MatrixNotifier;
use NotificationBot\WhatsApp\WhatsAppNotifier;
use NotificationBot\Slack\SlackNotifier;

class Notification
{
    public static function telegram(string $token, string $chatId): TelegramNotifier
    {
        return new TelegramNotifier($token, $chatId);
    }

    public static function matrix(string $homeserver, string $accessToken, string $roomId): MatrixNotifier
    {
        return new MatrixNotifier($homeserver, $accessToken, $roomId);
    }

    public static function whatsapp(string $token, string $phoneNumberId, string $to): WhatsAppNotifier
    {
        return new WhatsAppNotifier($token, $phoneNumberId, $to);
    }

    public static function slack(string $webhookUrl): SlackNotifier
    {
        return new SlackNotifier($webhookUrl);
    }
}
