<?php

namespace NotificationBot\Matrix;

use NotificationBot\Contracts\NotifierInterface;

class MatrixNotifier implements NotifierInterface
{
    private string $homeserver;
    private string $accessToken;
    private string $roomId;

    public function __construct(string $homeserver, string $accessToken, string $roomId)
    {
        $this->homeserver  = rtrim($homeserver, '/');
        $this->accessToken = $accessToken;
        $this->roomId      = $roomId;
    }

    /**
     * Send text message
     */
    public function send(string $message): bool
    {
        return $this->sendEvent([
            'msgtype' => 'm.text',
            'body'    => $message,
        ]);
    }

    /**
     * Send image
     */
    public function sendImage(string $imagePath, ?string $caption = null): bool
    {
        $mxcUrl = $this->uploadMedia($imagePath, mime_content_type($imagePath));

        if (!$mxcUrl) {
            return false;
        }

        return $this->sendEvent([
            'msgtype' => 'm.image',
            'body'    => basename($imagePath),
            'url'     => $mxcUrl,
            'info'    => [
                'mimetype' => mime_content_type($imagePath),
                'size'     => filesize($imagePath),
            ],
        ]);
    }

    /**
     * Send document
     */
    public function sendDocument(string $filePath, ?string $caption = null): bool
    {
        $mxcUrl = $this->uploadMedia($filePath, mime_content_type($filePath));

        if (!$mxcUrl) {
            return false;
        }

        return $this->sendEvent([
            'msgtype' => 'm.file',
            'body'    => basename($filePath),
            'url'     => $mxcUrl,
            'info'    => [
                'mimetype' => mime_content_type($filePath),
                'size'     => filesize($filePath),
            ],
        ]);
    }

    /**
     * Upload media to Matrix
     */
    private function uploadMedia(string $filePath, string $mimeType): ?string
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $url = "{$this->homeserver}/_matrix/media/v3/upload?access_token={$this->accessToken}";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: {$mimeType}"
            ],
            CURLOPT_POSTFIELDS     => file_get_contents($filePath),
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if (!empty($error)) {
            return null;
        }

        $result = json_decode($response, true);

        return $result['content_uri'] ?? null;
    }

    /**
     * Send message event to room
     */
    private function sendEvent(array $content): bool
    {
        $txnId = uniqid('txn_', true);

        $url = "{$this->homeserver}/_matrix/client/v3/rooms/"
            . urlencode($this->roomId)
            . "/send/m.room.message/{$txnId}?access_token={$this->accessToken}";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_PUT            => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($content),
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if (!empty($error)) {
            return false;
        }

        $result = json_decode($response, true);

        return isset($result['event_id']);
    }
}
