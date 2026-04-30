# Exercise 04 — Abstract Classes

Read [materials/04-abstract.md](../../materials/04-abstract.md) first.

## Brief

Model a small set of payment methods using an abstract base.

### Requirements

`PaymentMethod.php`:

- `abstract class PaymentMethod`.
- Constructor-promoted `protected int $amountCents`.
- `abstract public function charge(): string;` — no body.
- Concrete `public function receipt(): string` — returns
  `"Charged $X.YZ via <runtime class>"`. Use `static::class` and
  `number_format`.
- Concrete `protected function format(): string` — returns the
  formatted dollar amount. `charge()` implementations should reuse
  `$this->format()`.

Two concrete subclasses, each in its own file:

`CreditCardPayment.php`:

- Extends `PaymentMethod`.
- Adds `private string $last4` (overrides constructor, calls
  `parent::__construct`).
- `charge()` returns `"Charged {amount} to card ending {last4}"`.

`PayPalPayment.php`:

- Extends `PaymentMethod`.
- Adds `private string $email`.
- `charge()` returns `"Charged {amount} via PayPal ({email})"`.

### Driver script

`run.php`:

1. Build one of each, call `charge()` and `receipt()` on both, echo the
   results.
2. Inside try/catch, attempt `new PaymentMethod(100)` and prove the
   abstract-class fatal hits.
3. Bonus: write a `function processAll(array $payments)` that iterates
   any list of `PaymentMethod` objects and calls each one. Type-hint
   the parameter against the abstract class.

### Acceptance

`php exercises/04-abstract/run.php` prints two charge lines, two
receipt lines, and the caught abstract-instantiation error.

## How to signal "done"

Same drill.
