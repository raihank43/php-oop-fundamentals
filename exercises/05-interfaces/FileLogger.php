<?php

declare(strict_types=1);

require_once __DIR__ . '/Logger.php';

class FileLogger implements Logger
{
    public function __construct(private readonly string $path) {}

    public function info(string $message): void
    {
        file_put_contents($this->path, '[INFO] [' . date('c') . "] {$message}\n", FILE_APPEND);
    }

    public function error(string $message): void
    {
        file_put_contents($this->path, '[ERROR] [' . date('c') . "] {$message}\n", FILE_APPEND);
    }
}
