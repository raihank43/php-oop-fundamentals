# Exercise 03 — Inheritance & Method Overriding

Read [materials/03-inheritance.md](../../materials/03-inheritance.md) first.

## Brief

Model a small notification hierarchy.

### Requirements

`Notification.php`:

- Class `Notification` with:
  - `protected string $recipient`, `protected string $message`
    (constructor-promoted).
  - `public function send(): string` — returns `"-> {recipient}:
    {message}"`. (We're not actually sending anything; just returning a
    string we can `echo`.)
  - `public function describe(): string` — returns
    `"<class>: <recipient>"` using `static::class`. (Yes, `static::`,
    not `self::` — module 07 explains why; for now, copy and observe
    the difference.)

`UrgentNotification.php`:

- Extends `Notification`.
- Overrides `send()` to prepend `[URGENT] ` to the parent's output.
  **Must** call `parent::send()` — don't reimplement.

`SmsNotification.php`:

- Extends `Notification`.
- Adds `private string $sender` (constructor parameter).
- **Overrides the constructor.** Must call `parent::__construct(...)`.
- Overrides `send()` to format as `"SMS from {sender} -> ..."` reusing
  the parent.

`FinalEmailNotification.php`:

- `final class FinalEmailNotification extends Notification` — declared
  `final` so nothing can extend it.
- Overrides `send()` to format as `"Email -> ..."`.

### Driver script

`run.php`:

1. Instantiate one of each, call `send()` and `describe()` on all.
2. Inside try/catch, attempt something forbidden — e.g. write a
   `class X extends FinalEmailNotification {}` in the same file and
   observe the fatal at parse time. (Comment it out before running for
   the demo, but write a sentence in STRUGGLES.log about what happened
   when you tried.)

### Acceptance

`php exercises/03-inheritance/run.php` runs and you can see how `static::class`
inside `describe()` returns the **runtime** class (`UrgentNotification`,
not `Notification`).

## How to signal "done"

Same as before. If `static::class` confused you, log it — module 07
covers it but you can pre-empt the question now.
