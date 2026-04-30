# Exercise 01 — Classes, Constructors, Properties, Methods

Read [materials/01-classes.md](../../materials/01-classes.md) first.

## Brief

Build a `BlogPost` class and use it from a tiny script.

### Requirements

- File `BlogPost.php` with `declare(strict_types=1);` at the top.
- Class `BlogPost` with these typed properties (declare them through
  **constructor property promotion** — that's the modern PHP 8 shape):
  - `string $title`
  - `string $body`
  - `string $author`
  - `int $createdAt` — default to `time()` (handle this inside the
    constructor body, not the parameter list).
  - `?int $publishedAt` — defaults to `null`.
- The first three properties should be `public readonly` (immutable
  after construction). `$createdAt` should be `private readonly`.
  `$publishedAt` should be `private` (mutable — it gets set when you
  publish).
- A method `publish(): void` that sets `$publishedAt = time()` if it's
  not already set.
- A method `isPublished(): bool`.
- A method `summary(int $maxChars = 80): string` — returns the first
  `$maxChars` of the body, appending `...` if truncated. Multi-byte
  safe (`mb_substr` / `mb_strlen`) — this closes one of your prior open
  questions.
- A method `__toString(): string` returning something like
  `"{title}" by {author} (published)`.

### Driver script

A separate file `run.php` that:

1. Creates a `BlogPost`.
2. Prints the post (uses `__toString` implicitly via `echo`).
3. Calls `publish()`, prints again.
4. Tries to set `$post->title = 'new'` inside a `try { } catch
   (\Error $e) { }` and echoes the error message — proving `readonly`
   really blocks it at runtime.

### Acceptance

```bash
php exercises/01-classes/run.php
```

should print three lines (unpublished, published, the readonly error)
without crashing.

## How to signal "done"

Add any open questions to [STRUGGLES.log](../../STRUGGLES.log) with format
`#01-N — <question> [OPEN]`. Then tell Claude.
