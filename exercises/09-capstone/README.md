# Exercise 09 — Capstone: Tiny Order/Cart Domain

Read [materials/09-capstone.md](../../materials/09-capstone.md) first.

This is the integration project. It pulls every concept from modules
01–08 into one runnable codebase. By the time you're done, you should
be able to point at every line and say "that's why this OOP feature
exists."

## Brief

Build a CLI checkout script. The user shouldn't need to type any
arguments; `bin/checkout.php` hardcodes a sample cart and prints a
receipt + a log entry.

## Folder shape

```
exercises/09-capstone/
  composer.json
  bin/
    checkout.php
  app/
    Money.php                    ← App\Money — readonly value object, static factories
    Product.php                  ← App\Product — readonly value object
    Cart.php                     ← App\Cart — stateful, private items, public API
    Discount/
      DiscountStrategy.php       ← App\Discount\DiscountStrategy (interface)
      PercentageDiscount.php     ← implements DiscountStrategy
      FixedDiscount.php          ← implements DiscountStrategy
    Receipt/
      AbstractReceipt.php        ← App\Receipt\AbstractReceipt (abstract, abstract format())
      PlainTextReceipt.php       ← extends, implements format()
    Logger/
      Logger.php                 ← App\Logger\Logger (interface)
      FileLogger.php             ← implements Logger
      UrgentLogger.php           ← extends FileLogger, overrides log() to prepend [URGENT]
    Concerns/
      Timestamps.php             ← App\Concerns\Timestamps (trait)
```

`composer.json` maps `App\\` → `app/`.

## Acceptance criteria (Claude will check on review)

| # | Criterion | Where it lives |
|---|-----------|----------------|
| 1 | `php bin/checkout.php` runs end-to-end and prints a receipt | `bin/checkout.php` |
| 2 | Every class in the right namespace, every file PSR-4 | repo-wide |
| 3 | No `require` except `vendor/autoload.php` | repo-wide |
| 4 | Class with constructor + props + methods | `Product`, `Cart`, others |
| 5 | `public` / `protected` / `private` used deliberately | `Cart` (private items array) |
| 6 | Inheritance + `parent::` call | `UrgentLogger extends FileLogger` |
| 7 | Abstract class with at least one abstract method | `AbstractReceipt::format()` |
| 8 | Interface (one or more) | `DiscountStrategy`, `Logger` |
| 9 | Trait used in **two** classes | `Timestamps` on `Cart` and (your second class — `Receipt` recommended) |
| 10 | Static factory + late static binding | `Money::usd()` etc., return type `self` or `static` |
| 11 | `strict_types=1` on every file | repo-wide |
| 12 | Cart depends on `DiscountStrategy` interface, not a concrete class | `Cart::applyDiscount()` |

## Behavior

`bin/checkout.php` should:

1. Build 3–4 `Product` objects with varying `Money` prices.
2. Add them to a `Cart`.
3. Apply a `PercentageDiscount(10)` (or `FixedDiscount(Money::usd(500))`)
   via the cart.
4. Build a `PlainTextReceipt` from the cart and `echo` it.
5. Build an `UrgentLogger` pointed at `sys_get_temp_dir() .
   '/checkout.log'`, log `"checkout completed for total {amount}"`.
6. Print the log file contents at the end.

## Suggested order

1. `Money` (value object + factories) — easiest, no dependencies.
2. `Product` — uses `Money`.
3. `Cart` — needs `Product`, the trait, the discount interface.
4. The discount strategies — both interface implementers.
5. `Logger` interface, `FileLogger`, `UrgentLogger`.
6. `AbstractReceipt`, `PlainTextReceipt` (uses the trait too).
7. `bin/checkout.php` to wire it up.

## How to signal "done"

Run it. Eyeball the receipt. Check the log file. Run a final pass
against the table above. Then tell Claude.

If anything in the criteria is unclear, log it before starting — better
to clarify the spec than rework the whole thing.
