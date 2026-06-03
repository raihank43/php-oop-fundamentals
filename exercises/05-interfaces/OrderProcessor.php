<?php

declare(strict_types=1);

require_once __DIR__ . '/Logger.php';

class OrderProcessor
{
    public function __construct(
        private readonly Logger $logger
    ) {}

    public function process(int $orderId): void
    {
        $this->logger->info("processing order {$orderId}");
        $this->logger->info("order {$orderId} done");
    }

    public function fail(int $orderId, string $reason): void
    {
        $this->logger->error("order {$orderId} fail. Reason: {$reason}");
    }
}
