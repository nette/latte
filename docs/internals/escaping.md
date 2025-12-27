# Context-sensitive escaping

Latte's automatic escaping depends on **where** the output lands (HTML text vs an
attribute vs `<script>` vs `<style>` vs a URL), and it is implemented across **two
layers that must stay in sync**.

## The two-place invariant

- **Compile time** — `Compiler/Escaper.php` runs a context state machine
  (`enterHtmlText`/`Tag`/`Attribute`/`Comment`/`Raw`/`BogusTag`, plus
  `enterContentType` used by `{contentType}`) and, per
  `(contentType, state, subType)`, emits a string that **calls** a runtime escaping
  function (e.g. `LR\HtmlHelpers::escapeText(...)`, `LR\Helpers::escapeCss(...)`;
  plain-text output goes through the `escape` filter instead). The subType is not
  purely state-derived: `enterHtmlAttribute` infers `js` for `on*` and `css` for
  `style` attributes, and `enterHtmlText` classifies a `<script>` by its `type`
  attribute. `export()` serializes the state as a composite string
  (`'html/attr/js'`) — the key format used for block escaping and the convertor
  table below.
- **Runtime** — the actual escaping functions live in `Runtime\Helpers` (JS / CSS /
  iCal) and `Runtime\HtmlHelpers` / `XmlHelpers` (HTML / XML; note XML is not
  self-contained — XML comments and mandatory attribute escaping route to
  `HtmlHelpers`). The routines are single-source there, but the **context→function
  mapping exists twice** inside `Compiler\Escaper`: the
  `escape()`/`escapeMandatory()` match tables drive compile-time escaping, while
  the separate `Convertors` constant behind `Escaper::getConvertor` drives the
  runtime content-type conversion (`Helpers::convertTo`) and serves
  `Runtime\Template` both when including into an incompatible target type and as
  the block content-type compatibility predicate — `escape()` does not consult
  `getConvertor`. The two tables even use **different key spaces** (state constants
  vs. `export()`-style composite strings), which is exactly how they can silently
  diverge.

**Therefore: changing escaping in only one layer is a silent hole.** A new content
type or a changed escaping rule must be reflected in *both* `Compiler\Escaper` and the
`Runtime\*` helpers, or generated templates will call an escaper that behaves
differently than the compiler assumed.

## How nodes obtain the context: the `PrintContext` escaper stack

At print time the current escaping context lives in a **stack of `Escaper`s inside
`PrintContext`** (seeded by its constructor). `%escape` in `format()` resolves
against the stack's top. The trap: **`getEscaper()` returns a *clone*** — mutating
it (calling `enter*`) does not change the ambient context. A node that needs to
switch context for its *nested* output must push/pop via
`beginEscape()`/`restoreEscape()` and mutate the escaper `beginEscape()` returns; a
statement that changes the context for its *siblings* (like `{contentType}` inside
`<script>`) must instead *replace* the stack top (`restoreEscape()` followed by
`beginEscape()`) — pushing without a matching pop would desynchronize the enclosing
element's `restoreEscape()` and leak the context past the element.

## Dynamic attributes bypass the state machine

A whole HTML attribute generated from an expression (`Html\ExpressionAttributeNode`)
is not escaped via `Escaper` at all. The node picks a
`Runtime\HtmlHelpers::format*Attribute` method at compile time — the attribute *type*
(`bool`/`list`/`data`/`aria`/`style`) comes from the attribute name via
`classifyAttributeType`, or is forced by an attribute modifier: `|toggle` anywhere in
the chain, `|json` only as the **last** modifier (`json` never comes from the
attribute name). Any non-HTML content type falls back to
`XmlHelpers::formatAttribute`. These formatters do their own escaping (`escapeAttr`,
JSON smart-quoting), so they are a **third place** the escaping contract lives —
audit them alongside the two layers above.

## `ContentType` and contextual filters

`ContentType` is **not a PHP enum** — it is a `final class` of string constants
(`Html='html'`, `Text='text'`, `JavaScript`, `Css`, `Xml`, `ICal`). A **contextual
filter** takes a `FilterInfo` first parameter (a strict, non-nullable typehint — that
is how `FilterExecutor` detects it) and may set `$info->contentType`; that is how a
filter declares "my output is already HTML" (which disables further auto-escaping)
or asserts what input type it accepts (`FilterInfo::validate`). Filters applied to a
`{block}` should be contextual so the block's content type flows through them — but
the enforcement is **runtime, not compile time**: a classic filter is accepted when
the incoming content type is `text`, and throws only otherwise.
