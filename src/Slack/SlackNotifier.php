<?php

namespace NotificationBot\Slack;

use NotificationBot\Contracts\NotifierInterface;

class SlackNotifier implements NotifierInterface
{
    private string $webhookUrl;

    public function __construct(string $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function send(string $message): bool
    {
        $payload = json_encode([
            'text' => $message
        ]);

        $ch = curl_init($this->webhookUrl);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => $payload
        ]);

        curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return empty($error);
    }
}
