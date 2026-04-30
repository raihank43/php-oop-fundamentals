# 08 — Namespaces & Composer Autoloading (PSR-4)

This module directly answers your prior open question: "How is a real
PHP project structured? Namespaces, autoload, where do utility files
go?"

## TL;DR for a JS dev

A **namespace** is PHP's answer to a JS module path. It's the prefix
that disambiguates `App\Order` (your code) from `Stripe\Order` (a
package).

**Composer** is PHP's npm. It resolves dependencies and — more relevant
to this module — provides **autoloading**: you don't `require` files
manually, you just reference a namespaced class and Composer maps the
namespace to a file path on disk.

| JS | PHP |
|----|-----|
| `import { Order } from '@/orders/Order'` | `use App\Orders\Order;` |
| `package.json` | `composer.json` |
| `node_modules/` | `vendor/` |
| `npm install foo` | `composer require foo/bar` |
| Webpack/Vite resolves imports | Composer's autoloader resolves classes |

## Namespaces in code

Declared at the top of every file (immediately after `<?php` and any
`declare`):

```php
<?php
declare(strict_types=1);

namespace App\Orders;

class Order { /* ... */ }
```

To reference a class from another namespace, either use the fully
qualified name or pull it in with `use`:

```php
<?php
namespace App\Http;

use App\Orders\Order;        // import
use App\Money;
use App\Money as M;          // alias
use function App\format_money; // import a free function (rare)
use const App\TAX_RATE;        // import a constant (rarer)

class OrderController
{
    public function show(Order $order): string { /* ... */ }
}
```

Without the `use` line, you'd have to write `\App\Orders\Order` (with
the leading backslash — that's the "from the root" indicator, like
`/` in a filesystem path).

A class with no `namespace` declaration sits in the **global**
namespace. Standard library classes (`PDO`, `DateTime`, `Throwable`)
are global — that's why you see `\PDO` and `\DateTime` inside
namespaced files.

## PSR-4: the namespace ↔ filesystem rule

PSR-4 is a standards convention that says: a namespace prefix maps to a
directory, and every class lives in a file named after the class.

```
app/
  Orders/
    Order.php          → class App\Orders\Order
    OrderRepository.php → class App\Orders\OrderRepository
  Money.php            → class App\Money
```

You declare the mapping in `composer.json`:

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```

That says: anything starting with `App\` lives under `app/`, with
backslashes converted to slashes. After running `composer dump-autoload`
(or `composer install`), Composer generates `vendor/autoload.php`. You
include that **once** in your entry script:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use App\Orders\Order;

$order = new Order(/* ... */);
```

From then on, every reference to a class in the `App\` namespace gets
auto-loaded from the right file. No more `require_once` chains.

## Minimal project skeleton

```
my-project/
  composer.json
  vendor/                ← gitignored, Composer's output
  app/                   ← your code
    Orders/
      Order.php
      OrderRepository.php
    Money.php
  bin/
    run.php              ← entry script (require autoload.php + go)
```

`composer.json`:

```json
{
  "name": "raihank43/my-project",
  "type": "project",
  "require": {
    "php": "^8.1"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Tests\\": "tests/"
    }
  }
}
```

Run `composer install` once. From then on `composer dump-autoload`
regenerates the autoload map after you add new files. (PSR-4 doesn't
strictly need this — Composer can find new files on the fly — but
running `dump-autoload -o` once for prod gives you a flat optimized
classmap.)

## Naming conventions

- Namespaces: `StudlyCase` segments separated by `\` — `App\Http\Controllers`.
- Classes: `StudlyCase` — `OrderRepository`.
- File names: same as the class — `OrderRepository.php`. **Case-sensitive
  on Linux.** Don't ship `orderRepository.php`.

## Multiple PSR-4 roots, vendor packages, classmap

You can map several prefixes:

```json
"autoload": {
  "psr-4": {
    "App\\": "app/",
    "App\\Domain\\": "src/Domain/"
  }
}
```

Vendor packages register their own prefixes inside their `composer.json`.
After `composer install` they're all wired into `vendor/autoload.php`
automatically — that's why you can `use Carbon\Carbon;` after installing
`nesbot/carbon`.

The other two autoload styles you'll see — `classmap` (a flat list of
files to scan) and `files` (eagerly loaded, for global helper functions
like Laravel's `helpers.php`) — are escape hatches. PSR-4 is the
default.

## Free functions and constants in namespaces

Yes, namespaces apply to functions and constants too:

```php
namespace App\Util;

function slugify(string $s): string { /* ... */ }
const TAX_RATE = 0.07;
```

Reference as `App\Util\slugify(...)` or import with
`use function App\Util\slugify;`.

In practice, the team probably puts free helpers inside a class as
static methods, or inside a `helpers.php` file added to the `files`
autoload section. Class-based is more discoverable; `files`-based is
how Laravel ships its global helpers.

## Things that bite JS devs

1. **Backslashes everywhere.** `App\Orders\Order` — yes, backslashes,
   not slashes, not dots. PHP's namespace separator predates the modern
   consensus; we live with it.
2. **No file extensions in `use`.** Unlike JS imports.
3. **Case-sensitive on Linux.** Your laptop will let `app/orders/order.php`
   work; your CI runner won't. Match file name to class name exactly.
4. **`require __DIR__ . '/vendor/autoload.php';` is the one require you
   keep writing.** That's it. Don't manually `require_once 'app/Orders/Order.php';`
   ever again — Composer handles it.

## Checkpoint

- Write a `composer.json` with a PSR-4 mapping, run `composer install`,
  and reference a class from another file using only `use` (no
  `require`).
- Explain why CI on Linux might fail when local on Windows passes.
- Know the difference between `App\Order` and `\App\Order` (the leading
  backslash) and when each matters (almost never — but inside another
  namespace, the leading slash forces "from root").
