# Sandbox & the pass pipeline

## The sandbox pass

`Policy` is a five-method interface (`isTagAllowed`/`isFilterAllowed`/
`isFunctionAllowed`/`isMethodAllowed`/`isPropertyAllowed`). The `SandboxExtension`
registers a pass **only when a policy is set**, ordered `before: '*'` (before every
other pass), that traverses the AST in the **`leave`** phase and:

- statically forbids `$this`, the superglobals (`$_GET`, `$_SESSION`, …) and
  variable-variables, `|noescape`, and `new`;
- statically checks function and filter names against the policy — but even
  *allowed* functions and filters get their arguments rewritten through a runtime
  `args()` guard (wrapped in an `AuxiliaryNode`);
- **replaces** property/method fetches and calls with `Sandbox\Nodes\*` wrappers that
  emit a **runtime** `RuntimeChecker` call (`method`/`prop`/`call`; a partial
  application, first-class callable syntax included, emits `closure()` and applies
  the placeholders to the closure it returns, each bound argument passing through
  `args()`) — so method/property access is enforced at render time, not compile time;
- rewrites the pipe `$a |> $b`, which calls any callable, to a runtime `pipe()`;
- refuses `(array)` of an object at runtime and checks every public property of a
  non-Traversable object iterated by `{foreach}` (`castToArray`/`iterable`; a foreach
  by reference uses `iterableByRef`, which keeps the reference);
- routes the arguments of `|sort` and `|group` through `filterArgs()`: a property name
  in `by`/`byKey` would be read inside the filter, unseen, so it becomes a closure that
  reads the property through `prop()`. `RuntimeChecker::FilterPropertyParams` is the one
  place that knows these filters.

The pass can guard only what is a node, and only by replacing it. `getIterator()` of
every node must therefore yield each child **by reference to its slot**: a child that is
not yielded, yielded by value (a `readonly` property is, silently) or kept as a copy in
another node is printed as the parser left it, unchecked. `tests/common/Node.getIterator.phpt`
verifies it for every node class.

`method()` and `prop()` only check and return the receiver; the access itself stays a
native `->` or `?->`, so null handling and errors are PHP's own. Two traps follow, both
handled in `Nodes\MemberAccess`. A dynamic name is evaluated once into a temporary:
PHP reads the name of a write only after the right-hand side, so a second evaluation
would check one name and write another. The name of a property is also converted to
string there, once: `prop()` checks the string, and a `Stringable` name converted again
for the access could name another property. And PHP ends nullsafe short-circuiting at the
argument of the check, so every link after a `?->` is printed as `?->` too.

## Checks placed when printing

Two things the pass cannot guard, because they are not nodes: code a tag generates by
itself, and the escaping context, which is known only when printing.
`PrintContext::isSandboxed()` tells a node that it prints for the sandbox, and the node
emits the runtime check itself:

- **`iterator()`**: `{first}`, `{last}`, `{sep}`, `{skipIf}`, `{iterateWhile}` and `{else}`
  of `{foreach}` call methods of `$iterator`, an ordinary variable a template can
  overwrite with a host object. Each call goes through `ForeachNode::printIterator()`,
  which lets only a `CachingIterator` through. A tag that calls a method of a template
  variable must do the same.
- **`json()`**: `json_encode()` prints every public property of an object. Where a value
  is encoded to JSON (`Escaper` in the JavaScript contexts, the `data-*` and `|json`
  attributes, `n:attr`), no object may reach it, not even one nested in an array, a
  `JsonSerializable` or a `stdClass`. HTML and trusted content, printed as a string, is
  let through. The check wraps the value after its filters, the one that is encoded.

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

## Other boundaries of the sandbox

Like `__toString`, these are outside the policy by design and documented in the manual;
the protection is what the host passes to the template and what its policy allows:

- **Methods PHP calls on its own** run without asking the policy: `__clone` (`clone`),
  the `ArrayAccess` methods (`$obj['key']`, read or write), `getIterator`/`Iterator`
  methods (`{foreach}` over a `Traversable`, which `iterable()` lets through) and
  `Countable::count` (`|length`). Only `__get`/`__set` are covered, through the property
  check of the accessed name.
- **String callbacks inside allowed functions are not checked.** `args()` refuses only
  array callables; a string is indistinguishable from data, so allowing `array_map` or
  `usort` allows every function. `createSafePolicy()` therefore allows no function or
  filter taking a callback (`|filter`, `|map`, `|group`; `|sort` accepts only a `Closure`).
  Keep it that way when adding filters or functions to it.
- **Every `Closure` passes `checkCallable()`.** A template cannot create one from a
  forbidden callable (first-class callables go through `closure()`, `|>` through
  `pipe()`), so a `Closure` it holds was handed over by the host and is treated as a
  granted capability.
- **A filter or function that reads the state of an object by itself is not checked.**
  `|query`, `|escapeJs` and `|json` serialize an object with all its public properties,
  `|column` and the function `group()` read a property by its name. `createSafePolicy()`
  allows none of them; only `|sort` and the filter `|group` are guarded, through
  `filterArgs()`. Keep it that way when adding filters or functions to it.
- **Constants are not checked.** A template reads any global or class constant, enum
  cases included; the policy has no notion of them.

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
