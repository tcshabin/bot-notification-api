<?php

namespace NotificationBot\Contracts;

interface NotifierInterface
{
    public function send(string $message): bool;
}
