# Exercise 06 — Traits

Read [materials/06-traits.md](../../materials/06-traits.md) first.

## Brief

Write **one** trait, use it in **two unrelated classes** (the senior's
exact criterion). Pair it with an interface so the capability shows up
in the type system.

### Requirements

`HasTimestamps.php` (interface):

- `interface HasTimestamps`.
- Methods:
  - `public function touch(): void;`
  - `public function createdAt(): ?int;`
  - `public function updatedAt(): ?int;`

`Timestamps.php` (trait):

- `trait Timestamps`.
- Private state: `?int $createdAt = null`, `?int $updatedAt = null`.
- Implements `touch()`, `createdAt()`, `updatedAt()` matching the
  interface signatures.
- `touch()` sets `$createdAt` only if it's null, always updates
  `$updatedAt`. Use `time()`.

`Article.php`:

- `class Article implements HasTimestamps`.
- `use Timestamps;`.
- Constructor-promoted `public string $title` and `public string $body`.

`Comment.php`:

- `class Comment implements HasTimestamps`.
- `use Timestamps;`.
- Constructor-promoted `public string $author` and `public string $text`.

### Driver script

`run.php`:

1. Create an `Article` and a `Comment`.
2. Call `touch()` on both, sleep `1` second, call `touch()` on one
   again, print `createdAt` and `updatedAt` for both.
3. Show that the `Comment` `updatedAt` is now > `Article` `updatedAt`
   (proof the state is **per-instance**, not shared).
4. Write a `function recordTouch(HasTimestamps $obj): void` that calls
   `$obj->touch()` — pass both an `Article` and a `Comment`. The point
   is: the function depends on the **interface**, the trait provides
   the implementation.

### Acceptance

- The trait is reused **across two unrelated classes** with no
  inheritance link.
- The interface is the type both classes are passed as.
- `php exercises/06-traits/run.php` runs cleanly.

## Things to think about while you write it

- What if `Article` and `Comment` had two completely different
  timestamp policies (e.g. comments don't track `updatedAt`)? Would
  the trait still be the right tool? Add a STRUGGLES entry if you have
  a take.

## How to signal "done"

Same drill.
