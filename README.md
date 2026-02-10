📦 Notification Bot API

A lightweight, framework-agnostic notification API for sending messages via
Telegram and Matrix (Element).

✔ Open source
✔ Free
✔ No database
✔ No SDKs
✔ Works in Core PHP and Laravel
✔ Production-ready

✨ Supported Platforms
Platform	Text	Photo	Document
Telegram	✅	✅	✅
Matrix (Element)	✅	✅	✅
📋 Requirements

PHP 7.4+

cURL enabled

Composer

📦 Installation

Install via Composer:

composer require tcshabin/notification-bot-api

🧠 Basic Concept

This package provides:

Service-specific notifiers (Telegram, Matrix)

A unified entry point using Notification

You can use either approach.

🚀 Usage (Core PHP)
Telegram
use Tcshabin\NotificationApi\Notification;

Notification::telegram(
    'TELEGRAM_BOT_TOKEN',
    'CHAT_ID'
)->sendMessage('Hello from Core PHP 🚀');


Send photo:

Notification::telegram($token, $chatId)
    ->sendPhoto(__DIR__.'/photo.jpg', 'Photo caption');


Send document:

Notification::telegram($token, $chatId)
    ->sendDocument(__DIR__.'/file.pdf', 'File caption');

Matrix (Element)
use Tcshabin\NotificationApi\Notification;

Notification::matrix(
    'https://matrix.org',
    'ACCESS_TOKEN',
    '!ROOMID:matrix.org'
)->sendMessage('Hello Matrix 👋');


Send image:

Notification::matrix($server, $token, $room)
    ->sendPhoto(__DIR__.'/image.png', 'Matrix Image');


Send document:

Notification::matrix($server, $token, $room)
    ->sendDocument(__DIR__.'/file.pdf', 'Matrix File');

🚀 Usage (Laravel)
Step 1: Install package
composer require tcshabin/notification-bot-api

Step 2: Configure .env
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id

MATRIX_SERVER=https://matrix.org
MATRIX_TOKEN=your_access_token
MATRIX_ROOM=!roomid:matrix.org

Step 3: Add config (optional but recommended)

config/services.php

return [

    'telegram' => [
        'token'   => env('TELEGRAM_BOT_TOKEN'),
        'chat_id'=> env('TELEGRAM_CHAT_ID'),
    ],

    'matrix' => [
        'server' => env('MATRIX_SERVER'),
        'token'  => env('MATRIX_TOKEN'),
        'room'   => env('MATRIX_ROOM'),
    ],

];

Step 4: Use anywhere in Laravel
use Tcshabin\NotificationApi\Notification;

Notification::telegram(
    config('services.telegram.token'),
    config('services.telegram.chat_id')
)->sendMessage('Laravel Telegram Message 🚀');

Notification::matrix(
    config('services.matrix.server'),
    config('services.matrix.token'),
    config('services.matrix.room')
)->sendMessage('Laravel Matrix Message 🚀');

🔐 How to Get Credentials
Telegram

Create bot via @BotFather

Get BOT_TOKEN

Get CHAT_ID from chat or group

Matrix

Create account on https://matrix.org

Login and get access_token

Create a room and copy room_id

🏗 Project Structure
src/
├── Notification.php
├── Telegram/
│   └── TelegramNotifier.php
└── Matrix/
    └── MatrixNotifier.php

🧩 Why this package?

No database required

No framework lock-in

Simple HTTP-based implementation

Easy to extend (Slack, Discord, WhatsApp)

Suitable for microservices & cron jobs