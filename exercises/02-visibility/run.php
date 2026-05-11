<?php

declare(strict_types=1);

require __DIR__ . '/SavingsAccount.php';

$aliceBankAccount = new BankAccount("Alice", 100);
$aliceBankAccount->deposit(50);
$aliceBankAccount->withdraw(30);
echo $aliceBankAccount->balance(), PHP_EOL;

try {
    $aliceBankAccount->withdraw(-1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

$bobBankAccount = new SavingsAccount("bob", 1000);
$bobBankAccount->applyInterest();
$bobBankAccount->applyInterest();
echo $bobBankAccount->balance(), PHP_EOL;
