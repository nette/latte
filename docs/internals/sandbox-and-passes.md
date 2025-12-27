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
  emit a **runtime** `RuntimeChecker` call (`callMethod`/`prop`/`call`; first-class
  callable syntax emits `closure()`) — so method/property access is enforced at
  render time, not compile time.

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
