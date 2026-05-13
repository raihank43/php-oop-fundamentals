<?php

declare(strict_types=1);

require_once __DIR__ . '/PaymentMethod.php';

class PayPalPayment extends PaymentMethod
{

    public function __construct(
        int $amountCents,
        private string $email
    ) {
        return parent::__construct($amountCents);
    }

    public function charge(): string
    {
        $amount = $this->format();
        return "Charged {$amount} via PayPal ({$this->email})";
    }
}
