<?php

namespace Tcshabin\NotificationApi\WhatsApp;

class WhatsAppNotifier
{
    protected string $phoneNumberId;
    protected string $accessToken;
    protected string $apiVersion;

    public function __construct(
        string $phoneNumberId,
        string $accessToken,
        string $apiVersion = 'v18.0'
    ) {
        $this->phoneNumberId = $phoneNumberId;
        $this->accessToken  = $accessToken;
        $this->apiVersion   = $apiVersion;
    }

    /* -----------------------------
     | Send Text Message
     |------------------------------*/
    public function sendMessage(string $to, string $message): array
    {
        return $this->request([
            'messaging_product' => 'whatsapp',
            'to'   => $to,
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ]);
    }

    /* -----------------------------
     | Send Template Message
     |------------------------------*/
    public function sendTemplate(
        string $to,
        string $templateName,
        string $language = 'en_US'
    ): array {
        return $this->request([
            'messaging_product' => 'whatsapp',
            'to'   => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $language
                ]
            ]
        ]);
    }

    /* -----------------------------
     | Send Document
     |------------------------------*/
    public function sendDocument(
        string $to,
        string $documentUrl,
        string $filename = 'file.pdf'
    ): array {
        return $this->request([
            'messaging_product' => 'whatsapp',
            'to'   => $to,
            'type' => 'document',
            'document' => [
                'link' => $documentUrl,
                'filename' => $filename
            ]
        ]);
    }

    /* -----------------------------
     | HTTP Request
     |------------------------------*/
    protected function request(array $payload): array
    {
        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "Authorization: Bearer {$this->accessToken}"
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
