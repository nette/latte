# Filters & functions

The agent-facing catalog of filters and functions. The main tables cover the
default engine (`CoreExtension` + `SandboxExtension`); filters of the optional
extensions shipped with the package are listed separately. Usage and examples live
in the public manual at latte.nette.org — do not duplicate them here.

A **contextual** filter takes a `FilterInfo` first parameter and participates in the
content-type negotiation described in [escaping internals](../internals/escaping.md);
that column is derived from the source, don't edit it by hand.

Table rows are generated: run `php docs/reference/generate.php` after adding or
removing a filter or function. Only the *Notes* column is hand-maintained and
survives regeneration.

## Filters

<!-- generated: filters -->
| Filter | Implementation | Contextual | Notes |
|---|---|---|---|
| `batch` | `Filters::batch` |  |  |
| `breakLines` | `Filters::breaklines` |  |  |
| `breaklines` | `Filters::breaklines` |  | lowercase alias of `breakLines` |
| `bytes` | `Filters::bytes` |  |  |
| `capitalize` | `Filters::capitalize` |  | needs ext-mbstring |
| `ceil` | `Filters::ceil` |  |  |
| `checkUrl` | `Filters::checkUrl` |  | appended automatically to URL attributes by the `checkUrls` pass; opt out with `nocheck` |
| `clamp` | `Filters::clamp` |  |  |
| `column` | `Filters::column` |  |  |
| `commas` | `Filters::commas` |  |  |
| `dataStream` | `Filters::dataStream` |  |  |
| `datastream` | `Filters::dataStream` |  | lowercase alias of `dataStream` |
| `date` | `Filters::date` |  |  |
| `escape` | `Helpers::nop` |  | runtime nop, the real escaper is chosen by the compile-time context (see [escaping internals](../internals/escaping.md)) |
| `escapeCss` | `Helpers::escapeCss` |  |  |
| `escapeHtml` | `HtmlHelpers::escapeText` |  |  |
| `escapeHtmlComment` | `HtmlHelpers::escapeComment` |  |  |
| `escapeICal` | `Helpers::escapeICal` |  |  |
| `escapeJs` | `Helpers::escapeJs` |  |  |
| `escapeUrl` | `rawurlencode()` |  |  |
| `escapeXml` | `XmlHelpers::escapeText` |  |  |
| `explode` | `Filters::explode` |  |  |
| `filter` | `Filters::filter` |  |  |
| `first` | `Filters::first` |  |  |
| `firstLower` | `Filters::firstLower` |  | needs ext-mbstring |
| `firstUpper` | `Filters::firstUpper` |  | needs ext-mbstring |
| `floor` | `Filters::floor` |  |  |
| `group` | `Filters::group` |  |  |
| `implode` | `Filters::implode` |  |  |
| `indent` | `Filters::indent` | yes |  |
| `join` | `Filters::implode` |  | alias of `implode` |
| `last` | `Filters::last` |  |  |
| `length` | `Filters::length` |  |  |
| `limit` | `closure in CoreExtension` |  | wrapper over `Filters::slice` |
| `localDate` | `Filters::localDate` |  |  |
| `lower` | `Filters::lower` |  | needs ext-mbstring |
| `number` | `Filters::number` |  |  |
| `padLeft` | `Filters::padLeft` |  |  |
| `padRight` | `Filters::padRight` |  |  |
| `query` | `Filters::query` |  |  |
| `random` | `Filters::random` |  |  |
| `repeat` | `Filters::repeat` | yes |  |
| `replace` | `Filters::replace` | yes |  |
| `replaceRe` | `Filters::replaceRe` |  |  |
| `replaceRE` | `Filters::replaceRe` |  | alias of `replaceRe` |
| `reverse` | `Filters::reverse` |  |  |
| `round` | `Filters::round` |  |  |
| `slice` | `Filters::slice` |  |  |
| `sort` | `Filters::sort` |  |  |
| `spaceless` | `Filters::spaceless` | yes | one-shot minify, unlike the streaming `{spaceless}` tag |
| `split` | `Filters::explode` |  | alias of `explode` |
| `strip` | `Filters::spaceless` | yes | obsolete alias of `spaceless` |
| `stripHtml` | `Filters::stripHtml` | yes |  |
| `striphtml` | `Filters::stripHtml` | yes | lowercase alias of `stripHtml` |
| `stripTags` | `Filters::stripTags` | yes |  |
| `striptags` | `Filters::stripTags` | yes | lowercase alias of `stripTags` |
| `substr` | `Filters::substring` |  |  |
| `trim` | `Filters::trim` | yes |  |
| `truncate` | `Filters::truncate` |  |  |
| `upper` | `Filters::upper` |  | needs ext-mbstring |
| `webalize` | `Strings::webalize` |  | needs nette/utils |
<!-- /generated -->

## Filters of optional extensions

<!-- generated: filters-optional -->
| Filter | Extension | Implementation | Contextual | Notes |
|---|---|---|---|---|
| `translate` | `TranslatorExtension` | `closure in TranslatorExtension` | yes |  |
<!-- /generated -->

## Functions

<!-- generated: functions -->
| Function | Implementation | Notes |
|---|---|---|
| `clamp()` | `Filters::clamp` |  |
| `divisibleBy()` | `Filters::divisibleBy` |  |
| `even()` | `Filters::even` |  |
| `first()` | `Filters::first` |  |
| `group()` | `Filters::group` |  |
| `hasBlock()` | `closure in CoreExtension` |  |
| `hasTemplate()` | `closure in CoreExtension` |  |
| `last()` | `Filters::last` |  |
| `odd()` | `Filters::odd` |  |
| `slice()` | `Filters::slice` |  |
<!-- /generated -->
