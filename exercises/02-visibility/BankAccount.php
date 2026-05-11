<?php

declare(strict_types=1);

class BankAccount
{
    private float $balance;

    public function __construct(
        public readonly string $owner,
        float $opening_balance = 0.0
    ) {
        if ($opening_balance < 0) {
            throw new InvalidArgumentException("Negative Values are not allowed.");
        }

        $this->balance = $opening_balance;
    }

    public function deposit(float $amount): void
    {
        $this->assertPositive($amount, "Deposit");
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        $this->assertPositive($amount, "Withdraw");
        if ($amount > $this->balance) {
            throw new \InvalidArgumentException('Insufficient balance.');
        }
        $this->balance -= $amount;
    }

    public function balance(): float
    {
        return $this->balance;
    }

    private function assertPositive(float $amount, string $op): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("$op amount must be greater than zero.");
        }
    }

    // added new helper function for SavingsAccount to apply interest for balance.
    protected function setBalance(float $newBalance): void
    {
        if ($newBalance < 0) {
            throw new \InvalidArgumentException("Balance can't go negative.");
        }
        $this->balance = $newBalance;
    }
}
