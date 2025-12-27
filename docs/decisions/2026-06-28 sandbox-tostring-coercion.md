# Sandbox: implicit object-to-string coercion (`__toString`)

**Status:** Accepted · **Date:** 2026-06-28 · **Decided by:** David Grudl

## Context

The sandbox blocks an explicit `{$obj->__toString()}` call, but implicit
object-to-string coercion bypasses it: with `{$obj}`, concatenation,
interpolation, a `(string)` cast, and filters, `__toString()` runs even when the
method is not allowed by the policy. This was apparent during development, and a
decision was needed on whether to fix it in code or document it.

## Analysis (summary)

Coercion sites differ in how reliably they can be intercepted at compile time:

1. **Reliably interceptable (unconditional coercion):** output, concatenation
   `.`, `(string)` cast, interpolation. The operand is always converted, so a
   compile-time wrap would be correct.
2. **Unreliably or not at all:**
   * **Filters and functions** convert the argument inside their own body, where
     the sandbox has no visibility. Blanket-wrapping object arguments would
     wrongly block legitimate use (e.g. a `DateTime` passed to the `|date`
     filter). Detecting it via parameter reflection fails, because the result
     also depends on the file's `strict_types` and on explicit casts in the body.
   * **Loose comparison** (`==`, `<`, ...) coerces only against a string;
     `$obj == $otherObject` does not call `__toString()`. Compile-time wrapping
     of operands would therefore wrongly block legitimate object comparison.
3. The surface is further changed by `Feature::StrictTypes` (on by default, can
   be disabled).

**Severity is low.** What leaks is the return value of `__toString()`, which by
PHP convention is a displayable representation of the object, not arbitrary
internal state. The real risk only arises from a side-effecting, impure
`__toString()`, which is itself an anti-pattern, and the host can defend against
it by not placing such an object (or its source) into scope.

## Decision

**The code is unchanged; we address the problem with documentation.** The public
sandbox documentation (section "What the sandbox does not guard") was extended
with an explanation of the behavior and a clear rule for the host.

Reasons:

1. **A partial fix would not change the security advice.** Even if we intercepted
   the unconditional sites, filters, functions, and comparison stay open and
   cannot be closed cleanly. The advice "do not expose a dangerous `__toString`"
   holds regardless, so documentation carries the protection in any case.
2. **Cost asymmetry.** Such a fix would break legitimate use (printing a value
   object via `{$obj}`) with a breaking change, while an attacker just switches
   to a filter. It burdens the honest user more than the attacker.
3. **Unstable guarantee.** The surface depends on `Feature::StrictTypes`, which
   can be toggled.

Add to that the low severity (see above).

## Consequences

* Public documentation updated.
* Rule for the host: do not expose objects into a sandboxed template whose
  `__toString()` has side effects or exposes sensitive data - neither passed
  directly nor reachable through allowed operations.
* Should the decision change in the future (for principled consistency of
  `{$obj}` with `{$obj->__toString()}`), the path leads through intercepting the
  unconditional sites, an exception for `HtmlStringable`, and an upgrade guide.
  The residue (filters, functions, comparison) would still remain open.

## Rejected alternatives

* **Intercept the unconditional sites (output, concatenation, cast,
  interpolation).** Rejected for reasons 1-3.
* **Intercept filters and functions.** Not reliably possible (over-blocking
  legitimate objects, dependence on `strict_types` and on the called bodies).
* **Intercept comparison.** Would wrongly block object-to-object comparison.
