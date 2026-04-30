# Exercise 07 — Static & Late Static Binding

Read [materials/07-static.md](../../materials/07-static.md) first.

## Brief

Build a `Money` value object with named static factories (the
canonical idiom for constructors with multiple "shapes"), plus a small
`Entity` hierarchy that demonstrates `self::` vs `static::`.

### Requirements

`Money.php`:

- `final class Money`.
- `private function __construct(public readonly int $amountCents,
  public readonly string $currency)` — private so callers can't bypass
  the factories.
- Static factories: `usd(int $cents): self`, `eur(int $cents): self`,
  `idr(int $cents): self`.
- `public function add(Money $other): self` — throws
  `\InvalidArgumentException` if currencies differ.
- `public function format(): string` — `"$4.50 USD"` style.

`Entity.php` + `User.php` + `Product.php`:

- `abstract class Entity` with two static factories on it:
  - `public static function createWithStatic(): static { return new
    static(); }`
  - `public static function createWithSelf(): self { return new
    self(); }` — wait, can you `new self()` on an abstract class?
    Try it and see what happens. Add the answer to STRUGGLES.
  - To make `createWithStatic()` testable, give `Entity` a no-arg
    constructor (or none at all) and let subclasses inherit it.
- `class User extends Entity {}` — empty body.
- `class Product extends Entity {}` — empty body.

### Driver script

`run.php`:

1. Build `Money::usd(450)` and `Money::usd(50)`, add them, format —
   echo the result.
2. Try `Money::usd(100)->add(Money::eur(100))` inside try/catch.
3. Try `new Money(100, 'XYZ')` inside try/catch (bypass attempt — fails
   because the constructor is private).
4. Call `User::createWithStatic()` — confirm `get_class()` returns
   `'User'`.
5. Call `Product::createWithStatic()` — confirm `get_class()` returns
   `'Product'`. **This is the late-static-binding payoff.**
6. Try `Entity::createWithSelf()` and observe what happens. Add the
   answer to STRUGGLES.

### Acceptance

`php exercises/07-static/run.php` runs and the printed `get_class()`
results match the actual subclass — proving `static::` is "the class
that called me," not "the class where I'm defined."

## How to signal "done"

Same drill. Bonus points for explaining the abstract+`new self()`
behavior in STRUGGLES — that's exactly the kind of edge that makes
late static binding stick.
