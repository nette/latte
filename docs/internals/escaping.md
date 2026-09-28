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
  purely state-derived: `enterHtmlAttribute` infers `js` for `on*`, `css` for
  `style` and `html` for `srcdoc` attributes (and custom attributes, see below),
  and `enterHtmlText` classifies a
  `<script>` by its `type` attribute and gives the content of `<textarea>` and `<title>`
  (also nested elements in them, but not `<title>` in SVG) the subType `rcdata`. `export()` serializes the state as a composite string
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

## `srcdoc` is escaped twice

The value of `srcdoc` is an HTML document: the browser attribute-decodes it and then
parses the result as HTML. Escaping it only as an attribute is therefore an XSS —
`&lt;script&gt;` decodes to a live `<script>`, and an iframe without `sandbox` shares
the parent's origin. `HtmlHelpers::escapeHtmlAttr` escapes a value as HTML text first
and as an attribute second; an `Html` object passes through `escapeText` as markup
and is encoded only once, so trusted HTML keeps its tags. All three places route
`srcdoc` through it: the `html/attr/html` state in `escape()` and in the
`Convertors`, and `formatSrcdocAttribute` for dynamic attributes and `n:attr`.

With `Feature::MigrationWarnings`, `formatSrcdocAttribute` warns about a plain value
whose document differs from its text (it contains `<` or a character reference),
i.e. one that used to render as markup. Only the formatter paths warn: a mixed value
like `srcdoc="<p>{$x}</p>"` is escaped by code `Escaper::escape()` emits, and
`Escaper` has no access to feature flags.

## Braces are separated by a comment

Frameworks that compile templates from the DOM (Vue, petite-vue, AngularJS) read the
text after the browser has decoded entities, so `&#123;&#123;` is no protection against
`{{ }}` injection. `escapeText` and `escapeRawHtml` (`<script type="text/template">`)
insert `<!-- -->` after each `{` followed by another one or ending the string, and before
a leading one, so `{{` cannot form even from two adjacent outputs. An attribute cannot hold
a comment: the `&#123;` of `escapeAttr` does not stop AngularJS, which interpolates
attributes.

Inside `<textarea>` and `<title>` (`html/rcdata`) a comment is displayed as text and would
corrupt the value, so `escapeRcData` leaves braces alone. A block defined there is
`html/rcdata` too: filters see that type, and `convertRcDataToHtml` separates the braces
when such a block is included into ordinary text. A block from ordinary text included
there keeps its separators, since its output was escaped elsewhere.

## Custom attribute contexts

`Extension::getAttributeContexts()` maps attribute names, or prefixes ending with `*`,
to `js`, `css` or `html` (e.g. `x-on:*`, Tippy's `data-tippy-content`). A registered
attribute behaves exactly like its built-in counterpart `on*` / `style` / `srcdoc`.
Only HTML templates use the map; the XML escaper never reads it. No new states or
convertors exist: the value becomes the attribute subType, so `export()` yields the
existing `html/attr/js|css|html`. The same double escaping as `srcdoc` applies to
`html` attributes.

- `Engine::getAttributeContexts()` merges the maps (last registration wins),
  lowercases the keys and validates them. Built-ins can't be overridden: `on*`,
  `style`, `srcdoc`, and prefixes such as `o*`/`s*`/`*` are rejected, so the name
  checks in `enterHtmlAttribute` always run first. The map is part of the
  configuration signature, so extensions need not put it in `getCacheKey()`.
- `TemplateGenerator` seeds the `PrintContext` with an `Escaper` that carries the map.
  Clones inherit it.
- `HtmlHelpers::matchAttributeContext` (exact name, then longest prefix) is the single
  matcher. It is used in three places:
  - `enterHtmlAttribute`, at compile time;
  - `classifyAttributeType($name, $contexts)`, called by `ExpressionAttributeNode`;
  - `NAttrNode::attrs()`, at runtime. The compiled call passes the map as
    `contexts: [...]`, but only when the map is non-empty.

  A custom match wins over the bool/list/`data-`/`aria-` classification, so
  `data-tippy-content={$x}` is formatted as `srcdoc`, not JSON-encoded as `data`.

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
(`bool`/`tristate`/`valuedBool`/`list`/`data`/`aria`/`style`/`srcdoc`) comes from the attribute name via
`classifyAttributeType`, or is forced by an attribute modifier: `|toggle` anywhere in
the chain, `|json` only as the **last** modifier (`json` never comes from the
attribute name). Any non-HTML content type falls back to
`XmlHelpers::formatAttribute`. These formatters do their own escaping (`escapeAttr`,
JSON smart-quoting), so they are a **third place** the escaping contract lives —
audit them alongside the two layers above.

The categories are not interchangeable, because HTML resolves an *invalid* value
differently per attribute. `tristate` renders a bool as the `true`/`false` keyword,
but `valuedBool` must not: `hidden="false"` maps to the hidden state, so `false` there
has to drop the attribute instead. For the same reason `formatValuedBoolAttribute`
tests falsiness **before** treating the value as a keyword — narrowing that to
`is_string($value)` looks tidier but renders `hidden="0"` for a `'0'` coming from a
database, and the element silently disappears.

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
