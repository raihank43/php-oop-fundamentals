# 03 — Inheritance & Method Overriding

## TL;DR for a JS dev

`extends` works the same way it does in ES6. Two PHP-specific things:

- Call the parent with `parent::method()`, not `super.method()`.
- A class with no `extends` clause implicitly extends nothing — there's
  no `Object` base class you have to acknowledge.

| JS / TS | PHP |
|---------|-----|
| `class B extends A {}` | `class B extends A {}` |
| `super.foo()` | `parent::foo()` |
| `super(args)` | `parent::__construct(args)` |
| `final class A {}` (TS doesn't have it natively) | `final class A {}` |

## A minimum example

```php
<?php
declare(strict_types=1);

class Notification
{
    public function __construct(
        protected string $recipient,
        protected string $message,
    ) {}

    public function send(): string
    {
        return "Sending to {$this->recipient}: {$this->message}";
    }
}

class UrgentNotification extends Notification
{
    public function send(): string
    {
        $base = parent::send();
        return "[URGENT] {$base}";
    }
}

$n = new UrgentNotification('alice@example.com', 'server down');
echo $n->send();
// [URGENT] Sending to alice@example.com: server down
```

Notes:

- `protected` (not `private`) on the parent's properties — otherwise the
  child couldn't read them.
- The child's `send()` overrides the parent's. The signature must be
  **compatible** (same/wider parameters, same/narrower return type).
- `parent::send()` calls the original implementation. Same idea as
  React's `super.componentDidMount()` in old class components.

## Constructors and inheritance

Unlike ES6, PHP **does not** automatically call the parent constructor.
If you override `__construct`, you must call `parent::__construct(...)`
yourself when the parent has setup work to do:

```php
class Notification
{
    public function __construct(protected string $recipient) {}
}

class SmsNotification extends Notification
{
    public function __construct(string $recipient, private string $sender)
    {
        parent::__construct($recipient); // explicit
    }
}
```

If you forget, `$this->recipient` is uninitialized and reading it throws.
This is a top-3 PHP OOP gotcha for JS devs.

## `final` — opt out of inheritance

`final` on a class means "no one can extend this." `final` on a method
means "no one can override this method."

```php
final class Money { /* ... */ }   // no subclassing

class HttpClient
{
    final public function send(): string { /* ... */ }  // no overriding
}
```

Use `final class` liberally for value objects and infrastructure. Laravel
itself doesn't always do this, but modern PHP guidance says: be `final`
by default; remove it when you have a real reason to subclass. It rules
out a whole class of accidental coupling.

## Method override rules

A child can:

- Add a more permissive parameter type (contravariance) — `parent: int`,
  `child: int|string` — but this is rare and brittle, avoid it.
- Add a more restrictive return type (covariance) — `parent: object`,
  `child: User`. Common and useful.
- Widen visibility (`protected` → `public`).

A child cannot:

- Narrow a parameter type.
- Widen a return type.
- Reduce visibility.

PHP throws `Fatal error: Declaration of ... must be compatible with ...`
if you break this. Same rule TS enforces with `--strict`.

## Composition vs inheritance — the React lens

You're a React dev, so you've already absorbed "favor composition over
inheritance." Same advice in PHP. Reasons to actually `extend`:

- The child genuinely **is-a** parent (an `UrgentNotification` is a
  `Notification`).
- You're plugging into a framework's required base class
  (`Illuminate\Foundation\Http\FormRequest` in Laravel).
- You need to reuse 80%+ of the parent's behavior with one or two
  swaps.

If you find yourself reaching for inheritance to share two helper
methods, use a **trait** (module 06) or **plain composition** (inject
the helper as a dependency).

## Multiple inheritance

PHP doesn't have it. A class extends exactly one parent. For
"horizontal" sharing across unrelated classes, use traits (module 06)
or interfaces + composition (module 05).

## Things that bite JS devs

1. **Forgotten `parent::__construct`.** ES6 forces you to call `super()`;
   PHP does not. You'll just get an uninitialized property error later.
2. **Overriding a `final` method silently.** PHP throws — but the message
   ("Cannot override final method") is clear. Just respect it.
3. **`parent::` is `::`, not `->`.** `parent` is a keyword, not an
   instance, so you use the static-call operator. Same with `self::` and
   `static::` (module 07).

## Checkpoint

- Extend a class, override one method, and call back into the parent.
- Explain why `parent::__construct(...)` is mandatory when you override
  the constructor of a parent that has initialization.
- Decide whether your shared behavior should be inheritance, composition,
  or a trait.
