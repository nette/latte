# Sandbox & the pass pipeline

## The sandbox pass

`Policy` is a five-method interface (`isTagAllowed`/`isFilterAllowed`/
`isFunctionAllowed`/`isMethodAllowed`/`isPropertyAllowed`). The `SandboxExtension`
registers a pass **only when a policy is set**, ordered `before: '*'` (before every
other pass), that traverses the AST in the **`leave`** phase and:

- statically forbids `$this` / variable-variables, `|noescape`, and `new`;
- statically checks function and filter names against the policy — but even
  *allowed* functions and filters get their arguments rewritten through a runtime
  `args()` guard (wrapped in an `AuxiliaryNode`);
- **replaces** property/method fetches and calls with `Sandbox\Nodes\*` wrappers that
  emit a **runtime** `RuntimeChecker` call (`method`/`prop`/`call`; first-class
  callable syntax emits `closure()`) — so method/property access is enforced at
  render time, not compile time;
- refuses `(array)` of an object at runtime and checks every public property of a
  non-Traversable object iterated by `{foreach}` (`castToArray`/`iterable`; a foreach
  by reference uses `iterableByRef`, which keeps the reference).

`method()` and `prop()` only check and return the receiver; the access itself stays a
native `->` or `?->`, so null handling and errors are PHP's own. Two traps follow, both
handled in `Nodes\MemberAccess`. A dynamic name is evaluated once into a temporary:
PHP reads the name of a write only after the right-hand side, so a second evaluation
would check one name and write another. And PHP ends nullsafe short-circuiting at the
argument of the check, so every link after a `?->` is printed as `?->` too.

`Essential\CachingIterator` defines `__call` for the same reason the policy exists:
SPL would otherwise forward an unknown method called on `$iterator` to the inner
iterator, past a policy that allows all methods of `CachingIterator`.

Tags, filters and functions are checked at compile time, so the template cache must
tell policies apart: `SandboxExtension::getCacheKey()` uses the duck-typed
`getCacheSignature()` and triggers a deprecation for a policy without it.

Tags are gated separately (`isTagAllowed` in the parser), and the runtime checker is
exposed as the `sandbox` provider at `beforeRender`.

## The known gap: implicit `__toString` coercion

The sandbox blocks an explicit `{$obj->__toString()}`, but **implicit
object-to-string coercion is not guarded** — `{$obj}`, concatenation, interpolation, a
`(string)` cast, filters, and loose comparison all run `__toString()` even when the
method is not allowed. There is no branch for it in the sandbox visitor and no
`__toString` handling in `RuntimeChecker`. This is a **deliberate, documented** hole
(see the public `sandbox-tostring-coercion` decision): a partial compile-time fix
would break legitimate uses (printing a value object) while an attacker just switches
to a filter, and the surface shifts with `Feature::StrictTypes`. The protection is
the host rule "do not expose a dangerous `__toString`", not code. Do not "fix" this by
blanket-wrapping coerced operands.

## Extension & pass ordering

`Extension::order($subject, before:, after:)` returns a marker `{subject, before,
after}`. The two collections order differently:

- **Passes are topologically sorted** (`Helpers::sortBeforeAfter`, Kahn) across all
  extensions, with `'*'` meaning "relative to all others" — this is what lets the
  sandbox run `before: '*'`.
- **Tags are last-registered-wins.** `TemplateParser::addTags` simply overwrites the
  entry for a tag name, so if two extensions define the same tag, the later-registered
  extension's tag takes effect — a common surprise; `before`/`after` never decides
  which registration wins. For `{tag}` parsers the markers are dropped entirely; for
  **n:attributes** they are honored, but only to sort the order in which one
  element's attributes are processed (`completeAttrParsers`).
