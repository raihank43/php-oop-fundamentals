<?php

declare(strict_types=1);

class Notification
{
    public function __construct(
        protected string $recipient,
        protected string $message,
    ) {}

    public function send(): string
    {
        return "-> {$this->recipient}: {$this->message}";
    }

    public function describe(): string
    {
        $staticClass = static::class;
        $recipient = $this->recipient;
        return "<$staticClass>: <$recipient>";
    }
}
