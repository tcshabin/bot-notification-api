<?php

namespace Tcshabin\NotificationApi\Telegram;

class TelegramNotifier
{
    protected string $botToken;
    protected string $chatId;
    protected string $apiUrl;

    public function __construct(string $botToken, string $chatId)
    {
        $this->botToken = $botToken;
        $this->chatId   = $chatId;
        $this->apiUrl   = "https://api.telegram.org/bot{$botToken}/";
    }

    public function sendMessage(string $message): array
    {
        return $this->request('sendMessage', [
            'chat_id' => $this->chatId,
            'text'    => $message
        ]);
    }

    public function sendPhoto(string $photoPath, string $caption = ''): array
    {
        return $this->request('sendPhoto', [
            'chat_id' => $this->chatId,
            'photo'   => new \CURLFile($photoPath),
            'caption' => $caption
        ]);
    }

    public function sendDocument(string $filePath, string $caption = ''): array
    {
        return $this->request('sendDocument', [
            'chat_id'  => $this->chatId,
            'document' => new \CURLFile($filePath),
            'caption'  => $caption
        ]);
    }

    protected function request(string $method, array $params): array
    {
        $ch = curl_init($this->apiUrl . $method);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $params
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
