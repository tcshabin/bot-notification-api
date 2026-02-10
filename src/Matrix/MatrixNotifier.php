<?php

namespace Tcshabin\NotificationApi\Matrix;

class MatrixNotifier
{
    protected string $homeserver;
    protected string $accessToken;
    protected string $roomId;

    public function __construct(
        string $homeserver,
        string $accessToken,
        string $roomId
    ) {
        $this->homeserver  = rtrim($homeserver, '/');
        $this->accessToken = $accessToken;
        $this->roomId      = $roomId;
    }

    public function sendMessage(string $message): array
    {
        return $this->sendRoomMessage([
            'msgtype' => 'm.text',
            'body'    => $message
        ]);
    }

    public function sendPhoto(string $filePath, string $caption = ''): array
    {
        $upload = $this->uploadFile($filePath);

        return $this->sendRoomMessage([
            'msgtype' => 'm.image',
            'body'    => $caption ?: basename($filePath),
            'url'     => $upload['content_uri']
        ]);
    }

    public function sendDocument(string $filePath, string $caption = ''): array
    {
        $upload = $this->uploadFile($filePath);

        return $this->sendRoomMessage([
            'msgtype' => 'm.file',
            'body'    => $caption ?: basename($filePath),
            'url'     => $upload['content_uri']
        ]);
    }

    protected function sendRoomMessage(array $payload): array
    {
        return $this->request(
            'PUT',
            "/_matrix/client/r0/rooms/{$this->roomId}/send/m.room.message/" . uniqid(),
            $payload
        );
    }

    protected function uploadFile(string $filePath): array
    {
        $mime = mime_content_type($filePath);

        $ch = curl_init(
            "{$this->homeserver}/_matrix/media/r0/upload?access_token={$this->accessToken}"
        );

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ["Content-Type: {$mime}"],
            CURLOPT_POSTFIELDS     => file_get_contents($filePath),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    protected function request(string $method, string $uri, array $payload): array
    {
        $url = "{$this->homeserver}{$uri}?access_token={$this->accessToken}";

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
