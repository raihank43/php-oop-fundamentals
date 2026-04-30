# 09 — Capstone: A Tiny Order/Cart Domain

## Goal

Pull every concept from modules 01–08 into one small, runnable project.
The brief is intentionally small enough that you can finish in one
sitting, but rich enough to force every OOP feature into use.

## What you'll build

A command-line "checkout" script that:

1. Builds a few `Product` value objects.
2. Adds them to a `Cart`.
3. Applies a discount via a `DiscountStrategy` interface (two concrete
   strategies: percentage off, fixed amount off).
4. Prints a receipt.
5. Logs the checkout to a file via a `Logger` interface.

By the time you're done, you should have used:

| Concept | Where in the project |
|---|---|
| Class with constructor, properties, methods | `Product`, `Cart`, ... |
| `public` / `protected` / `private` visibility | `Cart` (private items array, public methods) |
| Inheritance + `parent::` | `UrgentLogger extends FileLogger` (or your variant) |
| Abstract class with abstract method | `AbstractReceipt` with abstract `format()` |
| Interface | `DiscountStrategy`, `Logger` |
| Trait used in two classes | `Timestamps` on `Cart` and `Receipt` |
| Static factory + late static binding | `Money::usd(...)` |
| Namespaces + Composer PSR-4 autoload | Whole project under `App\` |

## Folder shape

```
exercises/09-capstone/
  composer.json
  bin/
    checkout.php           ← entry point: require autoload + run
  app/
    Money.php              ← static factories, readonly value object
    Product.php            ← simple value object
    Cart.php               ← stateful, private items, public add/total
    Discount/
      DiscountStrategy.php       ← interface
      PercentageDiscount.php     ← implements
      FixedDiscount.php          ← implements
    Receipt/
      AbstractReceipt.php        ← abstract class
      PlainTextReceipt.php       ← extends, implements format()
    Logger/
      Logger.php                 ← interface
      FileLogger.php             ← implements
      UrgentLogger.php           ← extends FileLogger, overrides log()
    Concerns/
      Timestamps.php             ← trait used by Cart + Receipt
```

## Acceptance signals (Claude will check these on review)

- `bin/checkout.php` runs end-to-end with `php bin/checkout.php` and
  prints a recognizable receipt.
- Every class lives in the right namespace and the right file (PSR-4).
- No `require` statements anywhere except `vendor/autoload.php`.
- The discount logic is selected at runtime via the interface — the cart
  doesn't `if ($strategy instanceof PercentageDiscount)`.
- `UrgentLogger::log()` calls `parent::log()`.
- `AbstractReceipt` is genuinely abstract: `new AbstractReceipt()`
  fatals.
- The trait is used in **two** classes (the senior's exact criterion).
- Constructor property promotion is used.
- `strict_types=1` declared at the top of every file.

The exercise folder will have a more detailed `README.md` with the
acceptance test you should run yourself before saying "done."
