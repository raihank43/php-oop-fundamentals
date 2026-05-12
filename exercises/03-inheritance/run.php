<?php

declare(strict_types=1);

require __DIR__ . '/UrgentNotification.php';
require __DIR__ . '/SmsNotification.php';
require __DIR__ . '/FinalEmailNotification.php';

// TODO: drive all four classes per exercises/03-inheritance/README.md
$notification = new Notification("Raihan", "Hello World");
echo $notification->send(), PHP_EOL;
echo $notification->describe(), PHP_EOL;

$urgentNotification = new UrgentNotification("Kusuma", "Urgent Hello Message");
echo $urgentNotification->send(), PHP_EOL;
echo $urgentNotification->describe(), PHP_EOL;

$smsNotification = new SmsNotification("Alice", "SMS notif message", "Bob the Sender");
echo $smsNotification->send(), PHP_EOL;
echo $smsNotification->describe(), PHP_EOL;

$finalEmailNotification = new FinalEmailNotification("Final Sender", "Final Email notif message");
echo $finalEmailNotification->send(), PHP_EOL;
echo $finalEmailNotification->describe(), PHP_EOL;

// try {
//     class x extends FinalEmailNotification {}
// } catch (\Throwable $th) {
//     echo "Error: " . $th->getMessage();
// }
