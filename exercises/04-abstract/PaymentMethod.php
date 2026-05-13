<?php

declare(strict_types=1);

abstract class PaymentMethod
{
    public function __construct(
        protected int $amountCents
    ) {}

    abstract public function charge(): string;

    public function receipt(): string
    {
        return sprintf('Charged %s via %s', $this->format(), static::class);
    }


    protected function format(): string
    {
        // Handle negative values with proper placement of minus sign
        $sign = $this->amountCents < 0 ? '-' : '';
        $amount = abs($this->amountCents);

        return $sign . '$' . number_format($amount, 2, '.', ',');
    }
}
