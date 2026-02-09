<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST method allowed']);
    exit;
}

$config = require __DIR__ . '/config.php';
require __DIR__ . '/Telegram.php';

$type   = $_POST['type'] ?? null;
$chatId = $_POST['chat_id'] ?? null;

if (!$type || !$chatId) {
    http_response_code(400);
    echo json_encode(['error' => 'type and chat_id are required']);
    exit;
}

$telegram = new Telegram($config);

switch ($type) {

    case 'message':
        if (empty($_POST['message'])) {
            echo json_encode(['error' => 'message is required']);
            exit;
        }

        $response = $telegram->sendMessage(
            $chatId,
            $_POST['message']
        );
        break;

    case 'photo':
        if (!isset($_FILES['file'])) {
            echo json_encode(['error' => 'photo file is required']);
            exit;
        }

        $response = $telegram->sendPhoto(
            $chatId,
            $_FILES['file']['tmp_name'],
            $_POST['caption'] ?? ''
        );
        break;

    case 'document':
        if (!isset($_FILES['file'])) {
            echo json_encode(['error' => 'document file is required']);
            exit;
        }

        $response = $telegram->sendDocument(
            $chatId,
            $_FILES['file']['tmp_name'],
            $_POST['caption'] ?? ''
        );
        break;

    default:
        echo json_encode(['error' => 'Invalid type']);
        exit;
}

echo json_encode($response);
