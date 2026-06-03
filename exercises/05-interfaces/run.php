<?php

declare(strict_types=1);

require __DIR__ . '/StdoutLogger.php';
require __DIR__ . '/FileLogger.php';
require __DIR__ . '/OrderProcessor.php';


$logPath = sys_get_temp_dir() . "/orders.log";

$stdoutLogger = new StdoutLogger();
$orderProcessor = new OrderProcessor($stdoutLogger);

$orderProcessor->process(1);
$orderProcessor->fail(2, 'card declined');

$fileLogger = new FileLogger($logPath);
$newOrderProcessor = new OrderProcessor($fileLogger);
$newOrderProcessor->process(3);

echo file_get_contents($logPath);
