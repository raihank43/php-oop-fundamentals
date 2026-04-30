# Exercise 02 — Visibility

Read [materials/02-visibility.md](../../materials/02-visibility.md) first.

## Brief

Build a `BankAccount` class that protects its balance. The point is to
**prove** that visibility actually does what the keyword says.

### Requirements

`BankAccount.php`:

- `private float $balance` — the only mutable state.
- `public readonly string $owner`.
- Constructor takes `string $owner` and an optional opening balance
  (defaults to `0.0`). Reject negative opening balances by throwing
  `\InvalidArgumentException`.
- `public function deposit(float $amount): void` — rejects `<= 0`.
- `public function withdraw(float $amount): void` — rejects `<= 0` and
  throws if it would overdraw.
- `public function balance(): float` — read-only access to the balance.
- `private function assertPositive(float $amount, string $op): void` —
  the shared validation helper. Used by `deposit` and `withdraw`.

`SavingsAccount.php`:

- Extends `BankAccount`.
- Adds `protected float $interestRate` (set via constructor, defaults
  to `0.02`).
- `public function applyInterest(): void` — multiplies the balance.
  **Problem:** `$balance` is `private` on the parent, so the child
  can't read it. You have two options:
  1. Promote the parent's `$balance` to `protected` and document why.
  2. Add a `protected function setBalance(float $b): void` helper to
     the parent that the child uses.
  Pick option 2 (it preserves encapsulation better) and explain why in
  STRUGGLES if anything is unclear.

### Driver script

`run.php`:

1. Create a `BankAccount('alice', 100)`.
2. Deposit `50`, withdraw `30`, print the balance.
3. Attempt an invalid op (e.g. `withdraw(-1)`) inside try/catch and
   print the error.
4. Create a `SavingsAccount('bob', 1000)` with default interest, apply
   interest twice, print the balance.

### Acceptance

`php exercises/02-visibility/run.php` runs without crashing and prints
sensible numbers.

Bonus: try to do `$account->balance = 0;` from outside and observe the
error. Add the result to STRUGGLES if it surprises you.

## How to signal "done"

Same as exercise 01 — log open questions, tell Claude.
