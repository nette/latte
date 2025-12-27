# To My Agents!

It is my fervent wish that this file guide every AI coding agent working with code in this repository.

## Documentation

Any distilled, agent-facing documentation for this package - how it works
internally and the rationale behind key design decisions - lives in `docs/`.
Consult it before non-trivial changes; it is the source of truth from which the
public manual is distilled.

Latte is a compiler with several independent, trap-rich subsystems (code
generation, context-sensitive escaping, block inheritance, the sandbox, the
whitespace minifier). Read `docs/internals/` before editing any of them - a subtle
mistake here is a silent security hole, not just a bug.

`docs/reference/` catalogs the tags, filters and functions of the default engine
and the optional extensions.
Its table rows are generated - after adding or removing a tag, filter or function,
run `php docs/reference/generate.php` (only the *Notes* column is hand-edited).

## Project Overview

Latte is a secure, fast templating engine: it compiles template syntax into native
PHP classes with **context-sensitive automatic escaping** (HTML/XML/JS/CSS), is
extensible via tags/filters/functions/passes, and ships a template linter.

- **PHP Version**: 8.2+
- **Package**: `latte/latte`

## Essential Commands

```bash
# Run all tests
vendor/bin/tester tests -s        # or: composer tester
vendor/bin/tester tests/filters/ -s

# Static analysis
composer phpstan

# Lint templates (also: --strict, --debug)
vendor/bin/latte-lint path/to/templates
```

## Conventions

- PHP 8.2+; every file starts with `declare(strict_types=1);`; **tabs**; two blank
  lines between methods; return type and opening brace on separate lines; Nette
  Coding Standard (`ncs.xml`). Document the shut-up operator (`@mkdir($dir); // @ -
  directory may already exist`).
- Tests are Nette Tester `.phpt`, grouped by area under `tests/` (`common`,
  `filters`, `linter`, `phpLexer`, `phpParser`, `phpPrint`, `runtime`, `sandbox`,
  `tags`, `types`).

## Working in this repo

The pipeline is lexer -> `TemplateParser` -> AST nodes -> passes -> `TemplateGenerator`
-> PHP class (cached). The traps are non-local:

- **`getIterator()` MUST yield child nodes by reference (`&`).** If it doesn't,
  replace/remove passes - including the **sandbox** - silently skip that child. This
  is the easiest way to introduce a security hole; verify it on every node class.
- **Escaping lives in TWO places** - `Compiler/Escaper.php` (compile time) and
  the `Runtime\Helpers`/`HtmlHelpers`/`XmlHelpers` classes (runtime). Changing
  only one leaves a silent gap. See `docs/internals/escaping.md`.
- **The sandbox pass does not catch implicit `__toString` coercion** - a known,
  documented gap; don't assume it does.
- **Tag registration is last-registered-wins; `Extension::order(before/after)`
  never decides which registration of the same tag wins** - it orders passes and
  the per-element processing order of n:attributes, nothing else.
- **`ContentType` and `Escaper` are `final class`es with string constants, not
  enums**; the lexer's raw-text mode is `script`/`style` only (not textarea/pre).
- **Block availability equals the exact runtime model, not a superset** - an
  intermediate template's local blocks are not available, and `{import}` works
  only from the template head. See `docs/internals/blocks.md`.
- **`{include expr (args)}` is a callable-parsing trap** - always separate arguments
  with a comma. Don't hand-edit generated templates in `tmp/`.
- User- and extension-author how-to (creating custom tags/filters/functions,
  compiler passes, the `NodeTraverser`/`PrintContext` API, the type system) is
  manual material and lives in the public web docs, not here.
