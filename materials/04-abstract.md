# 04 — Abstract Classes

## TL;DR for a JS dev

An **abstract class** is a class you cannot instantiate directly. It
defines a partial implementation plus one or more methods that
subclasses are required to implement.

| TS | PHP |
|---------|-----|
| `abstract class Shape { abstract area(): number; }` | `abstract class Shape { abstract public function area(): float; }` |
| `new Shape()` → compile error | `new Shape()` → `Error: Cannot instantiate abstract class` |

Same concept TS has. The runtime enforces it.

## When you reach for an abstract class

Two boxes must be ticked:

1. There's **shared implementation** that all variants need (otherwise
   use an interface — module 05).
2. There's **at least one piece** that only a concrete subclass can
   provide.

If only #1, use a regular class (subclasses can override but aren't
required to). If only #2, use an interface.

## A minimum example

```php
<?php
declare(strict_types=1);

abstract class PaymentMethod
{
    public function __construct(protected int $amountCents) {}

    abstract public function charge(): string;       // subclass must provide

    public function receipt(): string                // shared
    {
        return sprintf(
            'Charged %s via %s',
            number_format($this->amountCents / 100, 2),
            static::class,           // late static binding — module 07
        );
    }
}

class CreditCard extends PaymentMethod
{
    public function charge(): string
    {
        return "Charging credit card for {$this->amountCents} cents";
    }
}

class PayPal extends PaymentMethod
{
    public function charge(): string
    {
        return "Redirecting to PayPal for {$this->amountCents} cents";
    }
}

$cc = new CreditCard(450);
echo $cc->charge();   // ...
echo $cc->receipt();  // shared implementation
```

What's worth pointing at:

- `abstract public function charge(): string;` — declared, no body. The
  semicolon at the end (no `{ }`) marks it as abstract.
- `PaymentMethod` cannot be `new`'d directly.
- `CreditCard` and `PayPal` **must** implement `charge()` or PHP fatals
  at compile time: `Class CreditCard contains 1 abstract method...`.
- `static::class` returns the **runtime** class name (`CreditCard`), not
  the parent. We dig into `static::` vs `self::` in module 07.

## Abstract vs interface — when to pick which

| | Abstract class | Interface |
|---|----------------|-----------|
| Can hold state (properties) | Yes | No |
| Can hold concrete methods | Yes | Yes (PHP 8+, default methods) but rarely used |
| Can hold abstract methods | Yes | All methods are abstract by default |
| A class can have many | One only | Many |

Heuristic: if you need to share **state** across subclasses, abstract
class. If you only need to share a **contract**, interface.

You'll often combine them in Laravel: `abstract class FormRequest
implements ValidatesWhenResolved` — concrete shared logic in the
abstract base, contract surface in the interface.

## Constructors in abstract classes

Allowed and common. Subclasses inherit it just like any other class:

```php
abstract class Repository
{
    public function __construct(protected \PDO $db) {}
}

class UserRepository extends Repository
{
    public function findById(int $id): ?User { /* uses $this->db */ }
}
```

If a subclass overrides `__construct`, the same rule from module 03
applies: call `parent::__construct(...)` explicitly.

## Visibility on abstract methods

`abstract public function ...` and `abstract protected function ...` are
both legal. `abstract private function ...` is **not** — it would be
unimplementable, since subclasses can't see private members.

## Things that bite JS devs

1. **Abstract classes can have constructors that take dependencies** —
   subclasses still need to honor them. Same as TS abstract classes.
2. **Forgetting to implement an abstract method is a compile-time error**
   in PHP, not a runtime one. You'll see it the moment you try to load
   the file, not when you instantiate the subclass.
3. **You can't `new` an abstract class even via reflection** without
   special tricks — don't try, it's a smell.

## Checkpoint

- Declare an abstract class with one abstract method and one concrete
  method that calls the abstract one.
- Explain when you'd pick an abstract class over an interface, and
  vice-versa.
- Know that subclasses must implement every abstract method, no
  exceptions.
