# 07 — Static Members & Late Static Binding

## TL;DR for a JS dev

A static member belongs to the **class**, not an instance. Same as
`static` in TS / ES6 classes:

```ts
class Counter { static count = 0; }
Counter.count++;
```

```php
class Counter {
    public static int $count = 0;
}
Counter::$count++;   // note the double colon and the leading $
```

Two PHP-specific bits:

1. **Two operators** for static access: `::` for static
   members and class constants, `->` for instance members. Don't mix
   them up — `Counter->count` is a syntax error.
2. **`self::` vs `static::`** — the late static binding rabbit hole. Read
   on.

## Static properties and methods

```php
<?php
declare(strict_types=1);

class IdGenerator
{
    private static int $next = 1;

    public static function next(): int
    {
        return self::$next++;
    }
}

echo IdGenerator::next(); // 1
echo IdGenerator::next(); // 2
```

| TS | PHP |
|---------|-----|
| `static foo: T` | `public static T $foo;` |
| `static bar() {}` | `public static function bar(): T {}` |
| `Foo.bar()` | `Foo::bar()` |
| `this.constructor.foo` (ugly) | `static::$foo` (clean) |

## Class constants

```php
class HttpStatus
{
    public const OK = 200;
    public const NOT_FOUND = 404;
}

echo HttpStatus::OK;
```

Constants are implicitly static. Don't write `const` *and* `static` —
it's a parse error. Constants can have visibility modifiers in PHP 7.1+.

For grouped named constants, prefer **enums** in PHP 8.1+ (covered
briefly below).

## `self::` vs `static::` — late static binding

This trips everyone up. Quick rule:

- `self::` resolves to **the class where the keyword is written**.
  Compile-time.
- `static::` resolves to **the class that was actually called**.
  Runtime. This is "late static binding."

```php
class Animal
{
    public static function create(): static
    {
        return new static();   // late: resolves to whatever subclass called it
    }

    public static function createSelf(): self
    {
        return new self();     // early: always Animal
    }
}

class Dog extends Animal {}

$x = Dog::create();      // Dog instance
$y = Dog::createSelf();  // Animal instance — probably not what you wanted
```

If you write a static factory in a base class and want subclasses to
get instances of themselves, use `new static()` and return type
`static`. This is how most ORM-style `::find()` / `::create()` methods
in Laravel work — the parent defines them once, every child gets the
right return type.

`self::` is fine when you know you genuinely want the declaring class
(constants, base-class private state, sibling helper methods).

## Static factories — the idiomatic use

You'll see this pattern constantly in PHP and Laravel:

```php
class Money
{
    private function __construct(
        public readonly int $amountCents,
        public readonly string $currency,
    ) {}

    public static function usd(int $amountCents): self
    {
        return new self($amountCents, 'USD');
    }

    public static function eur(int $amountCents): self
    {
        return new self($amountCents, 'EUR');
    }
}

$price = Money::usd(450);
```

Why? The constructor is private — callers cannot do `new Money(...,
'XYZ')`. Named static factories give you readable, validated entry
points.

## Statics are global state

The `IdGenerator` example above is convenient but dangerous. Static
state is process-wide global state, with all the classic problems:

- Hard to test (one test polluting another's state).
- Can't have two instances with different config.
- Awkward in long-running PHP processes (queue workers, async
  frameworks).

Static methods that don't touch static state (factories, pure
helpers) are fine. **Static state itself is a smell** — reach for it
sparingly.

## Singletons — the warning

```php
class Config
{
    private static ?self $instance = null;
    public static function instance(): self {
        return self::$instance ??= new self();
    }
}
```

Don't. The Laravel container (and most frameworks) gives you "singleton
lifetime" without making your class know it's a singleton. Code that
does `Config::instance()->get('foo')` is harder to test and harder to
swap out. Inject `Config` instead.

## Enums (PHP 8.1) — quick mention

The right answer for grouped named constants is an enum:

```php
enum HttpStatus: int
{
    case Ok = 200;
    case NotFound = 404;
    case ServerError = 500;
}

function handle(HttpStatus $status): void { /* ... */ }
handle(HttpStatus::Ok);
```

Type-safe, exhaustive, can have methods. Use enums over `class
Whatever { public const X = ...; }` whenever the values form a
closed set. Fuller treatment in a later module if you want one.

## Things that bite JS devs

1. **`::` is not `.`.** `Foo::bar` (static), `$foo->bar` (instance).
   Mixing them gives parse errors, not runtime errors — easy to spot.
2. **`self::` looks like `this`. It isn't.** It's the **class** the code
   was written in, captured at compile time. If you mean "the class
   that was actually called," use `static::`.
3. **Static state is shared across the whole process.** In long-running
   PHP (Octane, queue workers, RoadRunner), bugs from forgetting this
   are gnarly. Treat statics as if they leak between requests — because
   in modern setups they do.

## Checkpoint

- Write a class with a private constructor and two named static
  factories (`fromArray`, `fromString` — whatever fits).
- Explain when you'd write `new static()` vs `new self()` and why.
- Argue why a singleton with `::instance()` is worse than constructor
  injection.
