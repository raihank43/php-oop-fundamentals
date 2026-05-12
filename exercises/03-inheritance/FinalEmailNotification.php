<?php

declare(strict_types=1);

require_once __DIR__ . '/Notification.php';

final class FinalEmailNotification extends Notification
{
    public function send(): string
    {
        $base = parent::send();
        return "Email -> $base";
    }
}
