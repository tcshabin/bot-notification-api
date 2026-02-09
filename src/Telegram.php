<?php

namespace Tcshabin\BotNotification;

use CURLFile;

class Telegram
{
    private string $botToken;
    private string $apiUrl;

    public function __construct(string $botToken)
    {
        $this->botToken = $botToken;
        $this->apiUrl   = 'https://api.telegram.org/bot' . $botToken;
    }

    private function sendRequest(string $method, array $data): array
    {
        $url = $this->apiUrl . '/' . $method;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            return [
                'ok' => false,
                'error' => curl_error($ch)
            ];
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function sendMessage(string $chatId, string $message): array
    {
        return $this->sendRequest('sendMessage', [
            'chat_id' => $chatId,
            'text'    => $message
        ]);
    }

    public function sendPhoto(string $chatId, string $filePath, string $caption = ''): array
    {
        return $this->sendRequest('sendPhoto', [
            'chat_id' => $chatId,
            'photo'   => new CURLFile($filePath),
            'caption' => $caption
        ]);
    }

    public function sendDocument(string $chatId, string $filePath, string $caption = ''): array
    {
        return $this->sendRequest('sendDocument', [
            'chat_id'  => $chatId,
            'document' => new CURLFile($filePath),
            'caption'  => $caption
        ]);
    }
}

