# PHP OOP Fundamentals

> Module 2 of my PHP learning track. Building on
> [php-basic-fundamentals-01](https://github.com/raihank43/php-basic-fundamentals-01).
> Same instructor setup: explainers in [materials/](materials/), my code in
> [exercises/](exercises/), open questions in [STRUGGLES.log](STRUGGLES.log),
> retrospective in JOURNEY.md when I finish.

## Why this exists

The previous module was almost entirely functional — `array_map`, closures, typed
free functions. Section 5 of that journey closed with three big open questions:

1. **When does the team reach for OOP / classes vs. functions?**
2. **Project structure** — namespaces, Composer autoload, separating
   utilities from runnable scripts.
3. **Error handling granularity** — broad `\Throwable` vs. specific exception
   classes (this lives in the exception class hierarchy, which is OOP).

Plus the senior's acceptance criteria for this module:

- ✓ All fundamentals of PHP OOP
- ✓ Class with a constructor, properties, and methods
- ✓ Implement an interface and understand why contracts matter
- ✓ Use `public` / `protected` / `private` correctly
- ✓ Extend a class and override a parent method
- ✓ Create and use a trait in two different classes
- ✓ Define an abstract class with at least one abstract method

The 9 modules below cover all of that, in the order I want to learn them.

## Curriculum

| # | Module | Material | Exercise |
|---|--------|----------|----------|
| 01 | Classes, constructors, properties, methods | [materials/01-classes.md](materials/01-classes.md) | [exercises/01-classes/](exercises/01-classes/) |
| 02 | Visibility — public / protected / private | [materials/02-visibility.md](materials/02-visibility.md) | [exercises/02-visibility/](exercises/02-visibility/) |
| 03 | Inheritance & method overriding | [materials/03-inheritance.md](materials/03-inheritance.md) | [exercises/03-inheritance/](exercises/03-inheritance/) |
| 04 | Abstract classes | [materials/04-abstract.md](materials/04-abstract.md) | [exercises/04-abstract/](exercises/04-abstract/) |
| 05 | Interfaces & contracts | [materials/05-interfaces.md](materials/05-interfaces.md) | [exercises/05-interfaces/](exercises/05-interfaces/) |
| 06 | Traits — horizontal composition | [materials/06-traits.md](materials/06-traits.md) | [exercises/06-traits/](exercises/06-traits/) |
| 07 | Static & late static binding | [materials/07-static.md](materials/07-static.md) | [exercises/07-static/](exercises/07-static/) |
| 08 | Namespaces & Composer autoload (PSR-4) | [materials/08-namespaces.md](materials/08-namespaces.md) | [exercises/08-namespaces/](exercises/08-namespaces/) |
| 09 | Capstone — tiny order/cart domain | [materials/09-capstone.md](materials/09-capstone.md) | [exercises/09-capstone/](exercises/09-capstone/) |

## Workflow per module

1. Read the explainer in [materials/](materials/).
2. Open the exercise folder, read `README.md`, and write the code.
3. If anything trips me up, add a numbered entry to [STRUGGLES.log](STRUGGLES.log).
4. Tell Claude I'm done — code review happens, struggles get answered inline,
   and if the exercise passes, it's committed and pushed. If it fails, I fix
   first.

## Prereqs

- PHP 8.1+ (this curriculum uses PHP 8 features: typed properties, enums,
  constructor property promotion, readonly, first-class callables).
- Composer (for module 08 onwards).

## My JS/React/TS background

The materials lean on TypeScript / ES6-class analogues whenever they help.
If a section just says "this works the same as JS," I'm not going to spend
words re-explaining it.
