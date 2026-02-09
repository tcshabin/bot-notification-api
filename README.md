# Telegram Message API (Core PHP)

A lightweight Core PHP API package to send Telegram messages, photos, and documents without database or framework.

## Features
- API-only
- No database
- Core PHP
- Send message, photo, document
- Secure token handling

## Requirements
- PHP 8+
- cURL enabled

## Setup
1. Clone repository
2. Create `config.php`
3. Add your Telegram bot token

```php
<?php
return [
    'bot_token' => 'YOUR_BOT_TOKEN',
    'api_url'   => 'https://api.telegram.org/bot'
];
