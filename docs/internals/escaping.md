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
  purely state-derived: `enterHtmlAttribute` takes `js`, `css` or `html` from the
  attribute content type table (`on*`, `style`, `srcdoc` and custom attributes, see below),
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
  the block content-type compatibility predicate (`Escaper::canBeOverridden()`, which
  leaves out the `JsLiterals` table of convertors into a JS string literal) — `escape()`
  consults `getConvertor` only for trusted objects (see below), never for strings. The two
  tables even use **different key spaces** (state constants vs. `export()`-style composite
  strings), which is exactly how they can silently diverge.

**Therefore: changing escaping in only one layer is a silent hole.** A new content
type or a changed escaping rule must be reflected in *both* `Compiler\Escaper` and the
`Runtime\*` helpers, or generated templates will call an escaper that behaves
differently than the compiler assumed. HTML or text included into JS (`<script>`, `on*`,
a JS template) becomes a JS string literal, never code, like a value printed inside it
(`f({$x})`). The whole value of a dynamic attribute (`onclick={$x}`) is code by design,
but `{include}` cannot tell it forms one. CSS has no such literal, so text and HTML
cannot be included into `<style>` or `style` at all.

## Printing converts from the content type of the value

Every printed value has a content type: a plain string is `text`, a `TrustedJavaScript`,
`TrustedCss` or `TrustedXml` (subclasses included) has the type of its class. Printing a
trusted object behaves exactly like `{include}` of a template of that type: the convertor
comes from `Escaper::getConvertor()` (with `JsLiterals` and the deprecated XML/HTML
bridge), the same type passes unchanged, and a missing convertor throws instead of falling
back to escaping. The `Convertors` table is the only place of these rules; no escaper keeps
its own list of accepted types. `HtmlStringable` objects (`TrustedHtml`) do not take this
route yet, the escapers recognize them themselves.

- `Escaper::escape()` puts the type test into the generated code, so that a string pays
  one `is_object()` and no extra call:
  `((is_object($ʟ_val = EXPR) ? LR\Helpers::convertTrusted($ʟ_val, 'html/raw/js') : null) ?? LR\Helpers::escapeJs($ʟ_val))`.
  `EXPR` is evaluated once; a print nested in it is safe, since the outer one reads
  `$ʟ_val` only after it has finished. The whole is parenthesized because the result is
  spliced into larger expressions (`%escape()`).
- The context is `export()`, so **a state `escape()` handles must export a name the table
  knows**, otherwise every trusted object printed there throws.
- `Helpers::convertTrusted()` returns null for any other object, which falls through to
  the escaper. `escapeMandatory()` (`|noescape`) does not convert, it casts to a string.
- The formatters of dynamic attributes and `n:attr` get the raw value, so they convert
  inside, after branching on null, bool and array and instead of escaping; converting
  in front of them would escape twice. The context follows the attribute type:
  `formatJsAttribute` uses `html/attr/js`, `formatStyleAttribute` `html/attr/css`,
  `formatSrcdocAttribute` `html/attr/html`, `XmlHelpers::formatAttribute` `xml/attr` and
  the rest `html/attr`. A plain string as the whole value of a code attribute is still code.
- Values nested in arrays (class lists, style arrays, JSON) are not converted.
- For an HTML object `convertTrusted()` returns null and only checks the escaper against the
  rule: where no convertor from `html` exists (CSS, iCal, inside a tag, `<script>` of an
  unknown type) or the escaper prints it otherwise (a `text` template keeps the markup), it
  triggers `E_USER_DEPRECATED` and the output stays. `HtmlHelpers::formatAttribute()` escapes
  it with `escapeAttr()` whatever the attribute type, so it passes `htmlEscapedAs: 'html/attr'`;
  this is what tells the deprecated `onclick={$h}` from `onclick="f({$h})"`, which shares its
  context and agrees with the rule. In XML the escapers print it as XML, which `{capture}`
  relies on, so the check uses the `xml` convertors and never the XML/HTML bridge.
  `Nette\HtmlStringable` is checked too, except where `escapeAttr()` and the XML escapers treat
  it as a plain string.

## Rendering to a trusted object

`Engine::renderToTrusted()` wraps the output in the trusted class of the content type the
output really has, which is known only at runtime: `Template::render()` returns it. The type
comes from the template that printed the output, i.e. the last one of the `{extends}`
chain, not the one the engine was given; a single block has the `Block::$contentType` of the
block found there, which for an overridden block is the type of the overriding one, as its
function is the one that runs. A subtype maps to its main type: `html/raw/js` and
`html/raw/css` are valid code as they are, any other `html/*` or `xml/*` goes through
`getConvertor(type, 'html' or 'xml')` and throws where there is none (a comment, an `on*` or
`style` attribute, `<script>` of another type). `text` and `ical` throw, their output is no
trusted content.

## XML shares the HTML states but not their names

The XML escaper runs the same state machine as HTML (`$state` is `HtmlText`,
`HtmlAttribute`, …), but `export()` names those states after XML: `xml`, `xml/attr`,
`xml/comment`. Anything comparing an exported type against `html` therefore misses XML;
code that means "markup" (`FilterExecutor` unwrapping `HtmlStringable`, `{capture}`
wrapping its output back into `Html`, `|indent`) accepts both, and `{capture}` compares
against `Escaper::getContentType()` rather than a constant.

Exporting XML as `html` used to make XML and HTML convert into each other like HTML into
HTML (and XML into XML failed). `getConvertor` keeps those conversions working through
`getXmlHtmlConvertor`, which maps the XML side to its HTML twin (XML text to `html/rcdata`,
since neither separates braces) and triggers
`E_USER_DEPRECATED` as soon as such a convertor is looked up, so that overriding a
block (`Template::addBlock()` only asks whether one exists) is reported too. Conversions within XML, and from text, JS or CSS into
XML, have their own `Convertors` entries and use the XML escapers.

## `srcdoc` is escaped twice

The value of `srcdoc` is an HTML document: the browser attribute-decodes it and then
parses the result as HTML. Escaping it only as an attribute is therefore an XSS —
`&lt;script&gt;` decodes to a live `<script>`, and an iframe without `sandbox` shares
the parent's origin. `HtmlHelpers::escapeHtmlAttr` escapes a value twice as an attribute
value: Latte does not parse the document, so the value may land in its text as well as in
one of its attributes, where an unescaped quote would let it add an event handler. An
`Html` object is markup and is encoded only once, so trusted HTML keeps its tags, and so
is a value printed with `|noescape` (`escapeMandatory()`). All three places route
`srcdoc` through it: the `html/attr/html` state in `escape()` and in the
`Convertors`, and `formatSrcdocAttribute` for dynamic attributes and `n:attr`. A block
defined there becomes HTML by one attribute decoding (`convertHtmlAttrToHtml`), which
`getConvertor()` applies before converting it as HTML anywhere else.

With `Feature::MigrationWarnings`, `formatSrcdocAttribute` warns about a plain value
whose document differs from its text (it contains a tag or a character reference),
i.e. one that used to render as markup. Only the formatter paths warn: a mixed value
like `srcdoc="<p>{$x}</p>"` is escaped by code `Escaper::escape()` emits, and
`Escaper` has no access to feature flags.

## Braces are separated by a comment

Frameworks that compile templates from the DOM (Vue, petite-vue, AngularJS) read the
text after the browser has decoded entities, so `&#123;&#123;` is no protection against
`{{ }}` injection. `escapeText` (and `convertAttrToHtml`, `convertHtmlAttrToHtml`, `convertRcDataToHtml`) insert
`<!-- -->` after each `{` followed by another one or ending the string, and before a
leading one, so `{{` cannot form even from two adjacent outputs. An attribute cannot hold
a comment: the `&#123;` of `escapeAttr` does not stop AngularJS, which interpolates
attributes. Where Latte cannot tell text from an attribute (the content of
`<script type="text/template">` and of `srcdoc`), the value is escaped as an attribute:
Vue 3 compiles a template string before decoding entities, so `&#123;` protects there,
while Vue 2 decodes them first.

Inside `<textarea>` and `<title>` (`html/rcdata`) a comment is displayed as text and would
corrupt the value, so `escapeRcData` leaves braces alone. A block defined there is
`html/rcdata` too: filters see that type, and `Escaper::getConvertor()` converts it like
HTML text after `convertRcDataToHtml` has separated the braces, so it can be included
anywhere HTML can. A block from ordinary text included there keeps its separators, since
its output was escaped elsewhere. HTML printed or included there stays HTML, so filters
of the block still see its tags and the block included into ordinary text renders them;
`convertHtmlToRcData` therefore only neutralizes `</textarea` and `</title`, which would
end the element, also in a value printed with `|noescape`. Encoding every `<`
(`htmlspecialchars` without double encoding) would display the same in the element, but
the tags would be gone for the filters and the inclusion. Like braces, the end tag must
not form from adjacent outputs, so a `<` at the end of a value followed only by a start
of `/textarea` or `/title` is neutralized too: the `<` of an end tag split between
outputs is always in the first one.
The content is escaped for the element it was written in, so `validateTagChange()`
refuses `n:tag` and a dynamic tag name that would turn an element into `<textarea>` or
`<title>`, or one of them into another element.

## Attribute content types

One table maps attribute names, or prefixes ending with `*`, to `js`, `css` or `html`.
Its default entries are the built-ins in `HtmlHelpers::AttributeContentTypes`
(`'on*' => js`, `'style' => css`, `'srcdoc' => html`); there are no other hard-coded
checks of these names. `Extension::getAttributeContentTypes()` adds entries (e.g.
`x-on:*`, Tippy's `data-tippy-content`), so a registered attribute behaves exactly
like its built-in counterpart. Only HTML templates use the table; the XML escaper
never reads it. No new states or convertors exist: the value becomes the attribute
subType, so `export()` yields the existing `html/attr/js|css|html`. The same double
escaping as `srcdoc` applies to `html` attributes.

- `Engine::getAttributeContentTypes()` starts from the built-ins, adds the extensions'
  entries (last registration wins), lowercases the keys and validates them.
  Overriding a built-in is an exception: rejected is a key a built-in entry already
  matches (`onfoo`, `style`, `on*`, `srcdoc*`) and a prefix that would match a
  built-in name (`o*`, `s*`, `*`). The table is part of the configuration
  signature, so extensions need not put it in `getCacheKey()`.
- `TemplateGenerator` seeds the `PrintContext` with an `Escaper` that carries the
  table; an `Escaper` created without one gets the built-ins. Clones inherit it.
- `HtmlHelpers::matchAttributeContentType` (exact name, then longest prefix) is the single
  matcher. It is used in three places:
  - `enterHtmlAttribute`, at compile time;
  - `classifyAttributeType($name, $contentTypes)`, called by `ExpressionAttributeNode`;
  - `NAttrNode::attrs()`, at runtime. The compiled call passes the table as
    `contentTypes: [...]`, but only when it differs from the built-ins, which are the
    parameter's default.

  A match wins over the bool/list/`data-`/`aria-` classification, so
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
(`bool`/`tristate`/`valuedBool`/`list`/`data`/`aria`/`js`/`style`/`srcdoc`) comes from the attribute name via
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

URL attributes are checked in two places as well. The `checkUrls` pass appends
`|checkUrl` to an `ExpressionAttributeNode`, whose name is known at compile time. Names
in `n:attr` come at runtime, so `NAttrNode::formatHtmlAttribute()` calls `checkUrl()`
itself, and the compiled call carries the element name (`tag: 'a'`), because `data` is
a URL only in `<object>`. `n:attr` has no counterpart of `|nocheck`.

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
