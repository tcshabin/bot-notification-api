# Bot Notification API – Telegram (PHP & Laravel)

A lightweight, Composer-ready **Telegram notification package** for **Core PHP** and **Laravel** applications.  
Easily send **messages, photos, and documents** using the Telegram Bot API without any database or framework dependency.

---

## 🚀 Features

- ✅ Works with Core PHP
- ✅ Works with Laravel
- ✅ Composer installable
- ✅ Send text messages
- ✅ Send photos
- ✅ Send documents (PDF, ZIP, etc.)
- ❌ No database required
- ⚡ Lightweight and fast
- 🧱 Production ready

---

## 📦 Installation

Install the package using Composer:

```bash
composer require tcshabin/bot-notification-api


## Requirements
   PHP 8.0+

   cURL extension enabled

   Telegram Bot Token

🤖 Create Telegram Bot & Get Token

Open Telegram and search for @BotFather

Run the command:

/newbot


Follow the steps and copy your Bot Token

Keep the token secure (do not commit it)

🧪 Usage in Core PHP Project
Step 1: Install Package
composer require tcshabin/bot-notification-api

Step 2: Example Core PHP Script
<?php

require __DIR__ . '/vendor/autoload.php';

use Tcshabin\BotNotification\Telegram;

$botToken = 'YOUR_TELEGRAM_BOT_TOKEN';
$chatId   = 'CHAT_ID';

$telegram = new Telegram($botToken);

// Send text message
$telegram->sendMessage($chatId, 'Hello from Core PHP');

// Send photo
$telegram->sendPhoto(
    $chatId,
    '/absolute/path/to/photo.jpg',
    'Optional photo caption'
);

// Send document
$telegram->sendDocument(
    $chatId,
    '/absolute/path/to/file.pdf',
    'Optional document caption'
);

🌱 Usage in Laravel Project
Step 1: Install Package
composer require tcshabin/bot-notification-api

Step 2: Add Bot Token to .env
TELEGRAM_BOT_TOKEN=your_telegram_bot_token_here

Step 3: Configure config/services.php
'telegram' => [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
],

Step 4: Use in Controller or Service
<?php

namespace App\Http\Controllers;

use Tcshabin\BotNotification\Telegram;

class NotificationController extends Controller
{
    public function send()
    {
        $telegram = new Telegram(
            config('services.telegram.bot_token')
        );

        // Send text message
        $telegram->sendMessage(
            'CHAT_ID',
            'Hello from Laravel 🚀'
        );

        // Send photo
        $telegram->sendPhoto(
            'CHAT_ID',
            storage_path('app/public/photo.jpg'),
            'Laravel photo'
        );

        // Send document
        $telegram->sendDocument(
            'CHAT_ID',
            storage_path('app/public/file.pdf'),
            'Laravel document'
        );

        return response()->json([
            'status' => 'Notification sent successfully'
        ]);
    }
}

📄 Available Methods
Method	Description
sendMessage($chatId, $message)	Send a text message
sendPhoto($chatId, $filePath, $caption = '')	Send a photo
sendDocument($chatId, $filePath, $caption = '')	Send a document
📏 Telegram File Limits
Type	Maximum Size
Photo	~10 MB
Document	~50 MB
Bot Upload	Depends on Telegram