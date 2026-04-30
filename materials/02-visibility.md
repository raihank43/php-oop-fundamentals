# 02 — Visibility: public / protected / private

## TL;DR for a JS dev

PHP has three explicit visibility keywords. JS / TS only really has two
(public + private), and TS's `private` is a compile-time fiction —
nothing stops you from accessing it at runtime. PHP enforces visibility
**at runtime** and throws.

| Keyword | Who can access |
|---------|----------------|
| `public` | Anyone — inside the class, subclasses, outside callers. |
| `protected` | The class itself + any subclass. NOT outside callers. |
| `private` | Only the class itself. Subclasses cannot see it. |

If you omit visibility on a method, PHP defaults to `public`. **Always
write it explicitly** — the team will treat missing visibility as a
review nit.

## Map to TS / JS

| TS / JS | PHP |
|---------|-----|
| `public foo: T` (or no keyword) | `public T $foo` |
| `protected foo: T` | `protected T $foo` |
| `private foo: T` (TS, compile-time only) | `private T $foo` (runtime-enforced) |
| `#foo` (true private, runtime-enforced) | `private T $foo` (same semantics) |

The closest JS analogue to PHP's `private` is the `#`-prefix true
private field. PHP's `private` is also runtime-enforced.

## Why bother with `protected`?

`protected` exists for inheritance (next module). Use it when:

- You want subclasses to read/override a piece of internal state.
- You don't want outside callers touching it.

Example: an HTTP client base class might expose `protected function
buildHeaders()` so concrete subclasses can extend it, but the headers
shouldn't be a public API.

## Why bother with `private`?

`private` is your default for **state and helper methods that nobody
should touch from outside**. The benefit isn't paranoia — it's that
you're free to refactor those internals later without breaking callers.
The public surface is your contract; everything else is yours to change.

A common pattern:

```php
class OrderRepository
{
    public function __construct(private \PDO $db) {}

    public function find(int $id): ?Order
    {
        $row = $this->fetchRow($id);
        return $row ? $this->hydrate($row) : null;
    }

    private function fetchRow(int $id): ?array { /* ... */ }
    private function hydrate(array $row): Order { /* ... */ }
}
```

`find()` is the contract. `fetchRow` / `hydrate` are implementation
details — `private` because nobody outside `OrderRepository` should
care that they exist.

## Properties: public vs. private + getter

A frequent debate. The Laravel community is split, but the safer default
for **mutable** state is private + getter:

```php
// Risky: anyone can do $order->total = 999;
class Order { public int $totalCents; }

// Safer: state changes only via methods
class Order
{
    public function __construct(private int $totalCents) {}
    public function total(): int { return $this->totalCents; }
    public function applyDiscount(int $percent): void {
        $this->totalCents = (int) ($this->totalCents * (100 - $percent) / 100);
    }
}
```

For **immutable** value objects (Money, Email), `public readonly` is
fine and idiomatic — the value can't be tampered with anyway.

## Visibility on methods

Same three keywords, same semantics. Worth highlighting:

- A subclass **cannot** reduce visibility (you can't make a `public`
  parent method `protected` in the child). PHP throws.
- A subclass **can** widen visibility (`protected` → `public`).

This is the same rule TS enforces, just at runtime instead of compile
time.

## Visibility + constructor property promotion

The promotion shorthand from module 01 picks the visibility you tag the
parameter with:

```php
public function __construct(
    public readonly string $name,         // public, immutable
    private int $internalCounter = 0,     // private, mutable
) {}
```

Tag every promoted parameter — there's no "default to private."

## Things that bite JS devs

1. **No `_underscoreConvention` here.** A `private` keyword exists; use it
   instead of naming a public property `$_secret`. PHP devs will read the
   keyword, not the name.
2. **`private` in PHP truly hides from subclasses.** If a subclass needs
   to touch it, you wanted `protected`. Don't make everything `private`
   reflexively.
3. **Reflection can break visibility.** Just like JS Reflect proxies.
   This is fine — reflection is a deliberate escape hatch (Laravel uses
   it heavily for dependency injection). It's not a reason to leave
   things public.

## Checkpoint

- Pick the right keyword for: a config value passed in once and read
  forever / a counter that should change only via methods / a helper used
  only inside the class.
- Explain why `private` is safer than `public` for mutable state.
- Know that subclasses can see `protected` but not `private`.
