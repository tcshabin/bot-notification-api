<?php

namespace NotificationBot\Telegram;

use NotificationBot\Contracts\NotifierInterface;

class TelegramNotifier implements NotifierInterface
{
    private string $token;
    private string $chatId;
    private string $apiBase;

    public function __construct(string $token, string $chatId)
    {
        $this->token   = $token;
        $this->chatId  = $chatId;
        $this->apiBase = "https://api.telegram.org/bot{$this->token}/";
    }

    /**
     * Send text message
     */
    public function send(string $message): bool
    {
        return $this->request('sendMessage', [
            'chat_id' => $this->chatId,
            'text'    => $message,
        ]);
    }

    /**
     * Send photo
     */
    public function sendPhoto(string $photoPath, ?string $caption = null): bool
    {
        return $this->request('sendPhoto', [
            'chat_id' => $this->chatId,
            'photo'   => new \CURLFile($photoPath),
            'caption' => $caption,
        ], true);
    }

    /**
     * Send document
     */
    public function sendDocument(string $filePath, ?string $caption = null): bool
    {
        return $this->request('sendDocument', [
            'chat_id'  => $this->chatId,
            'document' => new \CURLFile($filePath),
            'caption'  => $caption,
        ], true);
    }

    /**
     * Core request handler
     */
    private function request(string $method, array $data, bool $multipart = false): bool
    {
        $url = $this->apiBase . $method;

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => $multipart ? [] : ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if (!empty($error)) {
            return false;
        }

        $result = json_decode($response, true);

        return isset($result['ok']) && $result['ok'] === true;
    }
}
