# Notification Bot API

A lightweight, open-source PHP notification package that allows you to send messages via:

- ✅ Telegram Bot API
- ✅ Matrix (Element) Protocol
- ✅ WhatsApp Cloud API (Free Tier)

Works with:
- ✅ Core PHP (no framework)
- ✅ Laravel (via Composer)

No database required.  
Ideal for alerts, logs, cron notifications, and micro-services.

---

## Features

- Simple API
- No database dependency
- PSR-4 autoloading
- Laravel friendly
- Extensible notification drivers
- Production ready

---

## Supported Channels

| Channel   | Free | Open Source | Media Support |
|---------|------|-------------|---------------|
| Telegram | ✅   | ❌ API (Free) | ✅ Photo, Document |
| Matrix   | ✅   | ✅           | ✅ File |
| WhatsApp| ✅ Free Tier | ❌ API | ❌ Text only |

---

## Installation

### Via Composer (Recommended)

```bash
composer require tcshabin/notification-bot-api
