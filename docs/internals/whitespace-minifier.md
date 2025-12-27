# WhitespaceMinifier

`{spaceless}` / `n:spaceless` / `|spaceless` are backed by
`Essential/WhitespaceMinifier` — a **stateful streaming tokenizer**. The tag forms
install it as an `ob_start` handler, so it minifies output as it flows rather than
buffering the whole document; the `|spaceless` filter instead calls a one-shot
`minify()` on an already-complete string. Its semantics depend on the content type (HTML collapses inter-tag
whitespace and drops it entirely around *whitespace-insensitive* elements; XML treats
all tags as insensitive; text/js/css/ical collapse spaces/tabs but keep newlines —
except in `/attr` contexts, where newlines collapse to a single space too; any
non-html/xml content type takes this text path, including `html/attr`).

Two invariants matter when editing it:

- **Every `TokenPattern` alternative must end with `>`.** This is an explicit,
  load-bearing contract (documented in the source): the chunk-boundary guard in
  `handle()` assumes an incomplete `<…` construct can only be completed by a later
  chunk that contains `>`, so it can safely carry an unfinished tail across an
  `ob_start` chunk boundary. Add an alternative that can end otherwise and the
  streaming rescan breaks on split input.
- **Nested `{spaceless}` is a no-op via a static depth counter, not a singleton.**
  `start()` increments `static $depth`; only the outermost call actually runs
  `ob_start`, and `end()` flushes when depth returns to 0. Output buffering is
  process-global, so nesting cannot mean nested buffers — describe it as a static
  depth guard, not an instance.

Note the minifier's notion of raw/insensitive elements is its **own policy**, distinct
from the compiler's: it protects the *contents* of `pre`/`textarea`/`script`/`style`
verbatim (`RawTextElements`, applied in HTML mode only — never XML) and treats a
different, broader set as whitespace-insensitive (including `br`/`hr`/`option`/
`link`…; `pre` sits in *both* sets). `script`/`style`/`select` are absent from the
insensitive set — presumably because dropping whitespace around them would glue
words, though the source states no rationale. This is why it differs from the
lexer's `script`/`style`-only raw-text.
