<?php

namespace Tcshabin\NotificationApi;

use Tcshabin\NotificationApi\Telegram\TelegramNotifier;
use Tcshabin\NotificationApi\Matrix\MatrixNotifier;

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
}
