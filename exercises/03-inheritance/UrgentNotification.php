<?php

declare(strict_types=1);

require_once __DIR__ . '/Notification.php';

class UrgentNotification extends Notification
{
    public function send(): string
    {
        $base = parent::send();
        return "[URGENT] {$base}";
    }
}
