# 01 — Classes, Constructors, Properties, Methods

## TL;DR for a JS dev

A PHP class looks almost identical to an ES6 / TS class. The differences
that matter on day one:

| JS / TS | PHP |
|---------|-----|
| `constructor()` | `__construct()` (always — never the class name) |
| `this.foo` | `$this->foo` (arrow, not dot — `$this` is required) |
| `new Foo()` | `new Foo()` (same) |
| `class Foo { name: string }` (TS field declaration) | `class Foo { public string $name; }` |
| `private name: string` (TS) | `private string $name;` |
| TS field shorthand: `constructor(private name: string)` | PHP 8 shorthand: `public function __construct(private string $name)` |

If you came from TS, the **constructor property promotion** in PHP 8 is
basically the TS shorthand you already use. It collapses declare + assign
into the parameter list.

## A minimum class

```php
<?php
declare(strict_types=1);

class Product
{
    public string $name;
    public int $priceCents;

    public function __construct(string $name, int $priceCents)
    {
        $this->name = $name;
        $this->priceCents = $priceCents;
    }

    public function priceFormatted(): string
    {
        return '$' . number_format($this->priceCents / 100, 2);
    }
}

$p = new Product('Coffee', 450);
echo $p->priceFormatted(); // $4.50
```

Read the syntax left-to-right and it maps cleanly to TS:

```ts
class Product {
  constructor(public name: string, public priceCents: number) {}
  priceFormatted(): string {
    return `$${(this.priceCents / 100).toFixed(2)}`;
  }
}
```

## PHP 8 constructor property promotion

The above class can shrink to:

```php
class Product
{
    public function __construct(
        public string $name,
        public int $priceCents,
    ) {}

    public function priceFormatted(): string
    {
        return '$' . number_format($this->priceCents / 100, 2);
    }
}
```

The `public` in the parameter list does two things at once:

1. Declares `$this->name` and `$this->priceCents` as properties.
2. Assigns the constructor argument to them.

This is **the** idiomatic modern PHP class shape. Use it unless you have a
reason not to (a property whose value is computed from other args, for
example).

## Typed properties

PHP properties carry a type just like TS fields:

```php
public string $name;
public int $priceCents;
public ?string $sku = null;   // nullable, like `string | null`
public array $tags = [];      // default value
```

A typed property must be initialized before being read. Reading an
uninitialized typed property throws `Error: Typed property ... must not be
accessed before initialization`. (TS will only warn at compile time;
PHP fails hard at runtime.)

## `readonly` properties (PHP 8.1)

```php
class Money
{
    public function __construct(
        public readonly int $amountCents,
        public readonly string $currency,
    ) {}
}

$m = new Money(450, 'USD');
$m->amountCents = 999; // Error: Cannot modify readonly property
```

`readonly` is the equivalent of TS `readonly` — assignable once (in the
constructor), then frozen. For value objects (Money, Email, UserId) this
is the default you want.

## Methods

Same shape as TS. Always declare the return type — strict mode is on.

```php
public function applyDiscount(int $percent): self
{
    return new self($this->name, (int) ($this->priceCents * (100 - $percent) / 100));
}
```

`self` as a return type means "an instance of this class." Use it for
chainable / immutable update methods.

## When does the team reach for OOP vs functions?

(Closing one of your prior open questions.)

The rough rule the Laravel ecosystem follows:

- **Class** when the thing has identity, state, or invariants you want to
  protect (`Order`, `Money`, `EmailAddress`, `HttpClient`, `UserRepository`).
- **Free function** for pure stateless transforms with no policy
  (`slugify($str)`, `formatMoney($cents)` if it's truly trivial).
- **In Laravel specifically:** controllers, models, services, jobs,
  requests, policies, mailables — all classes. You'll write 95% classes
  in real Laravel work. Free functions are mostly the global helpers
  Laravel ships with (`route()`, `config()`, `auth()`).

The functional patterns from the previous module didn't disappear — they
become useful inside class methods (`array_map` over `$this->items`).

## Things that bite JS devs

1. **`$this` is required.** No implicit `this` capture. Forgetting `$this->`
   inside a method gives "Undefined variable $foo" — PHP looks at the
   local scope, not the instance.
2. **`new` is mandatory** — there is no `Class.create()` factory pattern
   built in. Use `new Foo()` or a static factory method.
3. **Methods don't bind `$this` like JS arrow functions** — but you almost
   never pass methods around as callbacks the way you do in JS. When you
   do, use first-class callable syntax: `$callback = $obj->method(...)`.
4. **No optional chaining on properties yet** for old PHP, but PHP 8+ has
   the nullsafe operator: `$user?->profile?->email`.

## Checkpoint

Before moving on you should be able to:

- Write a class with typed properties and a typed-promoted constructor.
- Explain the difference between `$this->foo` and `$foo` inside a method.
- Decide when to mark a property `readonly`.
