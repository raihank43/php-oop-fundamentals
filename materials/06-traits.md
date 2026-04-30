# 06 — Traits: Horizontal Composition

## TL;DR for a JS dev

A **trait** is a chunk of methods (and optionally properties) that you
glue into a class with `use`. PHP copies them into the class as if you'd
written them inline. Traits exist because PHP has single inheritance —
they let you share behavior **across unrelated class hierarchies**
without `extends`.

The closest JS analogue is the old-school **mixin** pattern:

```js
const Loggable = (Base) => class extends Base {
  log(msg) { console.log(`[${this.name}]`, msg); }
};
class User extends Loggable(Object) {}
```

PHP traits are a first-class feature, not a hack:

```php
trait Loggable
{
    public function log(string $msg): void
    {
        echo "[" . static::class . "] {$msg}\n";
    }
}

class User    { use Loggable; }
class Order   { use Loggable; }
```

`User` and `Order` now both have a `log()` method. They share zero
ancestry.

## When to reach for a trait

Use it when:

- You have behavior that belongs in **multiple unrelated classes**.
- The behavior is **stateless** or owns its own private state cleanly.
- You don't want a base class because the classes don't share an
  is-a relationship.

Skip it when:

- The shared thing is a **dependency** (e.g., a logger object). Inject
  it through the constructor instead — that's composition, and it tests
  better.
- You're tempted to put business logic in a trait because two classes
  happen to need it. Extract a real collaborator class.

A clean rule of thumb: **traits are for capabilities, not collaborators.**
"Can be timestamped," "can be soft-deleted," "knows how to compute its
own slug" — those are capabilities. "Talks to the database" is a
collaborator.

## Anatomy

```php
<?php
declare(strict_types=1);

trait Timestamps
{
    private ?int $createdAt = null;
    private ?int $updatedAt = null;

    public function touch(): void
    {
        $now = time();
        $this->createdAt ??= $now;
        $this->updatedAt = $now;
    }

    public function updatedAt(): ?int
    {
        return $this->updatedAt;
    }
}

class Post
{
    use Timestamps;
    public function __construct(public string $title) {}
}

class Comment
{
    use Timestamps;
    public function __construct(public string $body) {}
}

$p = new Post('hello');
$p->touch();
echo $p->updatedAt(); // unix ts
```

A trait is **not** a type. You cannot type-hint `function foo(Timestamps
$x)`. Traits don't show up in `instanceof`. They're a code-sharing
mechanism, full stop. If you need a type, pair the trait with an
interface:

```php
interface HasTimestamps {
    public function touch(): void;
    public function updatedAt(): ?int;
}

class Post implements HasTimestamps {
    use Timestamps; // satisfies the interface
}
```

## Multiple traits + conflict resolution

```php
trait A { public function hello(): string { return 'from A'; } }
trait B { public function hello(): string { return 'from B'; } }

class X {
    use A, B {
        A::hello insteadof B;   // resolve conflict: A wins
        B::hello as helloFromB; // alias the loser
    }
}
```

In practice, conflicts are rare and a sign you're cramming too much
into traits. The syntax exists; you'll almost never use it.

## Visibility changes via `use`

You can adjust visibility when pulling a trait method in:

```php
class X {
    use SomeTrait {
        someMethod as protected;
    }
}
```

Niche. Mention it once, move on.

## Static methods in traits

Allowed. Each class that uses the trait gets its own copy of the static
method (and its own static state). This matters when you store
per-class registrars.

## Why Laravel uses traits everywhere

Eloquent models in Laravel use traits heavily: `SoftDeletes`,
`HasFactory`, `Notifiable`, `HasApiTokens`. Each of those is a
capability the model can opt into without inheritance. That's the
canonical "right" use of traits.

## Things that bite JS devs

1. **Traits are not types.** No `instanceof Trait`, no type-hinting. Pair
   with an interface if you need either.
2. **A trait copies code into the class.** It's not a delegation
   pattern. `static::class` inside a trait method returns the **using
   class's** name — that's intentional and useful.
3. **Property name collisions across traits cause errors.** If two
   traits declare a `$foo`, the using class won't compile. Different
   from method conflicts (which have the `insteadof` escape hatch).
4. **Don't simulate dependency injection with traits.** If your trait
   does `$this->logger->log(...)` and assumes the using class set up a
   `$logger`, that's an implicit contract that breaks when callers
   forget. Inject the logger.

## Checkpoint

- Write one trait and `use` it in two unrelated classes.
- Pair the trait with an interface so the capability shows up in the
  type system.
- Explain why a trait is a worse choice than constructor injection for
  passing in a logger.
