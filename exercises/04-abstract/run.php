<?php

declare(strict_types=1);

require __DIR__ . '/CreditCardPayment.php';
require __DIR__ . '/PayPalPayment.php';

$ccPayment = new CreditCardPayment(500, "8964");
echo $ccPayment->charge(), PHP_EOL;
echo $ccPayment->receipt(), PHP_EOL;

$ppPayment = new PayPalPayment(300, "raihan@mail.com");
echo $ppPayment->charge(), PHP_EOL;
echo $ppPayment->receipt(), PHP_EOL;


function processAll(PaymentMethod ...$payments): void
{
    foreach ($payments as $payment) {
        echo $payment->charge(), PHP_EOL;
    }
}
processAll($ccPayment, $ppPayment);

try {
    new PaymentMethod(100);
} catch (\Error $e) {
    echo 'Caught: ', $e->getMessage(), PHP_EOL;
}
