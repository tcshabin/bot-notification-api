<?php

namespace Tcshabin\NotificationApi;

use Tcshabin\NotificationApi\Telegram\TelegramNotifier;
use Tcshabin\NotificationApi\Matrix\MatrixNotifier;
use Tcshabin\NotificationApi\WhatsApp\WhatsAppNotifier;

class Notification
{
    public static function telegram(
        string $botToken,
        string $chatId
    ): TelegramNotifier {
        return new TelegramNotifier($botToken, $chatId);
    }

    public static function matrix(
        string $homeserver,
        string $accessToken,
        string $roomId
    ): MatrixNotifier {
        return new MatrixNotifier($homeserver, $accessToken, $roomId);
    }

    public static function whatsapp(
        string $phoneNumberId,
        string $accessToken,
        string $apiVersion = 'v18.0'
    ): WhatsAppNotifier {
        return new WhatsAppNotifier($phoneNumberId, $accessToken, $apiVersion);
    }
}
