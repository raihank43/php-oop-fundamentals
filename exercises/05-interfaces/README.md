# Exercise 05 — Interfaces & Contracts

Read [materials/05-interfaces.md](../../materials/05-interfaces.md) first.

## Brief

Build a logging contract with two implementations and a consumer that
depends only on the contract.

### Requirements

`Logger.php`:

- `interface Logger`.
- Methods:
  - `public function info(string $message): void;`
  - `public function error(string $message): void;`

`StdoutLogger.php`:

- `class StdoutLogger implements Logger`.
- `info()` writes `"[INFO] {msg}"` + `PHP_EOL` via `echo`.
- `error()` writes `"[ERROR] {msg}"` + `PHP_EOL` via `fwrite(STDERR,
  ...)`.

`FileLogger.php`:

- `class FileLogger implements Logger`.
- Constructor takes `string $path` — promoted, private, readonly.
- Both methods append `"[LEVEL] [iso-timestamp] {msg}\n"` to the file
  using `file_put_contents(..., FILE_APPEND)`.

`OrderProcessor.php`:

- `class OrderProcessor`.
- Constructor takes `Logger $logger` (type-hinted against the
  **interface**, not a concrete class). Promoted, private, readonly.
- `public function process(int $orderId): void` — logs `"processing
  order {id}"` at info level, then `"order {id} done"` at info level.
- `public function fail(int $orderId, string $reason): void` — logs
  the failure at error level.

### Driver script

`run.php`:

1. Build an `OrderProcessor` with a `StdoutLogger`. Call `process(1)`,
   `fail(2, 'card declined')`.
2. Build a second `OrderProcessor` with a `FileLogger` pointing at
   `/tmp/orders.log` (or platform equivalent — `sys_get_temp_dir()`
   handles cross-OS).
3. Call `process(3)` on the file-backed processor.
4. Print the contents of the log file at the end.

### Acceptance

- `OrderProcessor` never references `StdoutLogger` or `FileLogger` by
  name — only `Logger`.
- Swapping the implementation requires zero changes to `OrderProcessor`.
- `php exercises/05-interfaces/run.php` runs cleanly on your machine.

## Why this matters

This is the exact pattern Laravel uses for the entire ecosystem: every
concrete class depends on interfaces, the container binds the interface
→ implementation in one place. If you ever wondered "why are there so
many interfaces in vendor/laravel/framework?" — this is why.

## How to signal "done"

Same drill.
