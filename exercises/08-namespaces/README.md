# Exercise 08 — Namespaces & Composer Autoload (PSR-4)

Read [materials/08-namespaces.md](../../materials/08-namespaces.md) first.

This is the exercise where you stop writing `require_once` and let
Composer do the work.

## Brief

Take the `Logger` + `OrderProcessor` setup from exercise 05 and
**re-implement it under PSR-4 autoload** with a proper namespace.

### Requirements

Folder shape (relative to this exercise dir):

```
exercises/08-namespaces/
  composer.json
  bin/
    run.php
  src/
    Logger/
      Logger.php             ← interface  App\Logger\Logger
      StdoutLogger.php       ← class      App\Logger\StdoutLogger
      FileLogger.php         ← class      App\Logger\FileLogger
    OrderProcessor.php       ← class      App\OrderProcessor
```

`composer.json`:

```json
{
  "name": "raihank43/oop-08",
  "type": "project",
  "require": { "php": "^8.1" },
  "autoload": {
    "psr-4": { "App\\": "src/" }
  }
}
```

After writing it, run (from inside `exercises/08-namespaces/`):

```bash
composer install         # generates vendor/autoload.php
# or, if you already have a vendor dir:
composer dump-autoload
```

(If `composer` isn't on your PATH, install it from getcomposer.org. The
parent `.gitignore` already excludes `vendor/`.)

### Code requirements

- Every PHP file declares its namespace **immediately after** the
  `<?php` and `declare(strict_types=1);` lines.
- The interface lives at `App\Logger\Logger`. The two implementations
  live at `App\Logger\StdoutLogger` and `App\Logger\FileLogger`.
- `App\OrderProcessor` `use`s the interface — no `require_once`
  anywhere.
- `bin/run.php` is the **only** file with `require __DIR__ .
  '/../vendor/autoload.php';`.

### Driver script

`bin/run.php`:

1. Require the autoloader.
2. `use App\OrderProcessor;`, `use App\Logger\StdoutLogger;`, `use
   App\Logger\FileLogger;`.
3. Wire up an `OrderProcessor` with a `StdoutLogger`, run a couple of
   orders.
4. Wire up a second `OrderProcessor` with a `FileLogger` (use
   `sys_get_temp_dir() . '/orders-08.log'`), run an order, print the
   log contents.

### Acceptance

- `php exercises/08-namespaces/bin/run.php` runs and prints output.
- Zero `require_once` lines in `src/`. Only `bin/run.php` requires the
  autoloader.
- `composer.json` is committed. `vendor/` is **not** committed
  (gitignored at repo root).

## How to signal "done"

Same drill. Drop any namespace-vs-filesystem confusions into STRUGGLES
— the case-sensitivity gotcha trips a lot of people.
