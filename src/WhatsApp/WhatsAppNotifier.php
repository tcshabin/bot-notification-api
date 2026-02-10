<?php

namespace NotificationBot\WhatsApp;

use NotificationBot\Contracts\NotifierInterface;

class WhatsAppNotifier implements NotifierInterface
{
    private string $accessToken;
    private string $phoneNumberId;
    private string $to;
    private string $apiVersion = 'v18.0';

    public function __construct(string $accessToken, string $phoneNumberId, string $to)
    {
        $this->accessToken  = $accessToken;
        $this->phoneNumberId = $phoneNumberId;
        $this->to           = $to;
    }

    /**
     * Send text message (within 24-hour session window)
     */
    public function send(string $message): bool
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to'   => $this->to,
            'type' => 'text',
            'text' => [
                'body' => $message
            ],
        ];

        return $this->request($payload);
    }

    /**
     * Send template message (production safe)
     */
    public function sendTemplate(string $templateName, string $language = 'en_US', array $parameters = []): bool
    {
        $components = [];

        if (!empty($parameters)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(fn($value) => [
                    'type' => 'text',
                    'text' => $value
                ], $parameters),
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'       => $this->to,
            'type'     => 'template',
            'template' => [
                'name'     => $templateName,
                'language' => ['code' => $language],
                'components' => $components,
            ],
        ];

        return $this->request($payload);
    }

    /**
     * Core HTTP request handler
     */
    private function request(array $payload): bool
    {
        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if (!empty($error)) {
            return false;
        }

        $result = json_decode($response, true);

        return isset($result['messages'][0]['id']);
    }
}
