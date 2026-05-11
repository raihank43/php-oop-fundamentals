<?php

declare(strict_types=1);

require_once __DIR__ . '/BankAccount.php';

// TODO: implement SavingsAccount per exercises/02-visibility/README.md

class SavingsAccount extends BankAccount
{
    public function __construct(
        string $owner,
        float $openingBalance = 0.0,
        protected float $interestRate = 0.02,
    ) {
        parent::__construct($owner, $openingBalance);
    }

    public function applyInterest(): void
    {
        $this->setBalance($this->balance() * (1 + $this->interestRate));
    }
}
