<?php

declare(strict_types=1);

require_once __DIR__ . '/Logger.php';

class StdoutLogger implements Logger
{
    public function info(string $message): void
    {
        echo "[INFO] {$message}", PHP_EOL;
    }
    public function error(string $message): void
    {
        echo "[ERROR] {$message}", PHP_EOL;
    }
}
