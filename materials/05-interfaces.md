# 05 — Interfaces & Contracts

## TL;DR for a JS dev

A PHP `interface` is a **runtime-enforced** type contract. Closest analogues:

- TS `interface` — but TS interfaces vanish at compile time. PHP
  interfaces exist at runtime; you can `instanceof` them, type-hint
  against them, and the engine will throw if a class claims to implement
  one but doesn't.
- TS `abstract class` with all-abstract members — same mental model,
  except a PHP class can implement many interfaces.

| TS | PHP |
|---------|-----|
| `interface Logger { log(msg: string): void }` | `interface Logger { public function log(string $msg): void; }` |
| `class FileLogger implements Logger {}` | `class FileLogger implements Logger {}` |
| `class X implements A, B {}` | `class X implements A, B {}` |
| (TS interfaces vanish at runtime) | `$obj instanceof Logger` works at runtime |

## A minimum example

```php
<?php
declare(strict_types=1);

interface Logger
{
    public function log(string $message): void;
}

class FileLogger implements Logger
{
    public function __construct(private string $path) {}

    public function log(string $message): void
    {
        file_put_contents($this->path, $message . PHP_EOL, FILE_APPEND);
    }
}

class StdoutLogger implements Logger
{
    public function log(string $message): void
    {
        echo $message . PHP_EOL;
    }
}

function doWork(Logger $logger): void   // depends on the contract, not a class
{
    $logger->log('starting');
    // ...
    $logger->log('done');
}

doWork(new StdoutLogger());
doWork(new FileLogger('/tmp/app.log'));
```

The function `doWork` doesn't care whether it gets a `FileLogger` or a
`StdoutLogger`. It cares that whatever it gets fulfills the `Logger`
contract. This is the entire reason interfaces exist.

## Why contracts matter (the senior's exact phrasing)

Three concrete payoffs:

1. **Dependency injection.** Laravel's container resolves a class
   depending on `Logger` by looking up "what's bound to that
   interface?" You bind the interface once in a service provider and
   every consumer wires up automatically.
2. **Testability.** Production code depends on `MailerInterface`. Tests
   pass a `FakeMailer` that records calls. No HTTP, no SMTP, no
   waiting. Same idea as React Testing Library swapping a real fetch
   for a mock.
3. **Refactor safety.** You can swap `RedisCache` for `MemcachedCache`
   anywhere in the codebase as long as both implement `CacheInterface`.
   Callers never change.

If your code depends on a concrete class everywhere, you have to touch
every caller to swap implementations. If it depends on an interface,
you swap the binding in one place.

## Multiple interfaces

A class can implement as many interfaces as it likes:

```php
interface Loggable { public function log(string $msg): void; }
interface Cacheable { public function cacheKey(): string; }

class User implements Loggable, Cacheable
{
    public function __construct(private int $id, private string $name) {}
    public function log(string $msg): void { /* ... */ }
    public function cacheKey(): string { return "user:{$this->id}"; }
}
```

This is the closest PHP gets to multiple inheritance. Interfaces are
just contracts, not behavior — there's nothing to conflict.

## Interface inheritance

Interfaces can extend other interfaces (and unlike classes, they can
extend **multiple** parents):

```php
interface Readable { public function read(): string; }
interface Writable { public function write(string $data): void; }
interface ReadWritable extends Readable, Writable {}
```

A class implementing `ReadWritable` must implement both `read()` and
`write()`.

## Constants on interfaces

Interfaces can declare constants. Implementing classes inherit them.

```php
interface HttpStatus
{
    public const OK = 200;
    public const NOT_FOUND = 404;
}
```

Use sparingly — usually a dedicated `final class HttpStatus` (or PHP 8.1
enum) is cleaner.

## Default methods (PHP 8+)

Since PHP 8.0 interfaces can technically hold method bodies via... no,
they cannot. **Don't confuse PHP with Java 8.** PHP interfaces are
contract-only. If you need shared implementation, use a trait
(module 06) or an abstract class (module 04).

## `instanceof` and type juggling

```php
if ($logger instanceof FileLogger) { /* specific */ }
if ($logger instanceof Logger)     { /* contract */ }
```

Type-hint method parameters and return types against interfaces
whenever you can. Concrete types are a code smell at boundaries.

## Naming

PHP doesn't enforce a naming convention, but two patterns dominate:

- Suffix style: `LoggerInterface`, `RepositoryInterface`. Verbose but
  unambiguous in IDE autocomplete. Used heavily by Symfony.
- Bare style: `Logger`, `Repository`. The implementation gets the
  qualifier (`FileLogger`, `EloquentRepository`). Used heavily by
  Laravel.

Pick whichever your team uses. Don't mix.

## Things that bite JS devs

1. **PHP interfaces are real at runtime.** You can `instanceof` them, you
   can throw if a value isn't one — that's normal PHP code, not the
   compile-only world TS has trained you to expect.
2. **No structural typing.** TS will accept `{ log(msg: string): void }`
   wherever a `Logger` is expected. PHP requires the explicit
   `implements Logger` clause. Fail loudly is the PHP default.
3. **Method signatures must match exactly.** Including parameter types,
   return types, and (since PHP 8) parameter defaults. Mismatched
   signatures fatal.

## Checkpoint

- Declare an interface, implement it in two unrelated classes, and write
  a function that depends on the interface (not a concrete class).
- Explain to a teammate why interfaces unlock testability and DI.
- Know that interfaces hold contracts only — no implementation, no
  state.
