<?php

declare(strict_types=1);

require_once __DIR__ . '/Notification.php';

class SmsNotification extends Notification
{
    public function __construct(
        string $recipient,
        string $message,
        private string $sender,
    ) {
        parent::__construct($recipient, $message);
    }

    public function send(): string
    {
        $base = parent::send();
        return "SMS from {$this->sender} $base";
    }
}
