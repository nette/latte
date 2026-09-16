# Upgrading latte/latte

## 3.1.7

- **Renamed** `Latte\Compiler\Nodes\Php\Expression\FunctionCallNode::isPartialFunction()` → `Latte\Compiler\Nodes\Php\Expression\FunctionCallNode::isPartialApplication()`; the old name was removed (ee40c170)
- **Renamed** `Latte\Compiler\Nodes\Php\Expression\MethodCallNode::isPartialFunction()` → `Latte\Compiler\Nodes\Php\Expression\MethodCallNode::isPartialApplication()`; the old name was removed (ee40c170)
- **Renamed** `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode::isPartialFunction()` → `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode::isPartialApplication()`; the old name was removed (ee40c170)
- **Deprecated** `Latte\Compiler\NodeHelpers::toValue()`: call `Latte\Compiler\Nodes\Php\ExpressionNode::toValue()` on the node itself, `$node->toValue($constants)`; the old name is silently deprecated (4b67d6ef)
- **Deprecated** a `Latte\Policy` implementation without `getCacheSignature()`: add a method `getCacheSignature(): mixed` returning a value that identifies its rules; using such a policy triggers E_USER_DEPRECATED (d8ebbcbc)
- **Changed** `Latte\Sandbox\SecurityPolicy::allowProperties()` matches property names case-sensitively; previously allowing `visible` also allowed `Visible`; list every spelling the templates use (f95b3d16)
- **Changed** casting an object to array in a sandboxed template throws `Latte\SecurityViolationException`; previously it revealed all its properties (4efc8fb2)
- **Changed** `{foreach}` over a non-Traversable object in a sandboxed template checks each public property against the policy; previously the properties were read unchecked; allow the properties the templates iterate (4efc8fb2)
- **Changed** a method call on a non-object in a sandboxed template throws the native `\Error`; previously `Latte\SecurityViolationException`; the arguments of a `?->` call are no longer evaluated when the object is null; affects code catching it (640f4dc7)
- **Changed** `$iterator` of `{foreach}` (`Latte\Essential\CachingIterator`, marked @internal) no longer forwards unknown methods to the inner iterator; calling them throws `\Error` (0a7a3bd9)
- **Changed** `contenteditable`, `draggable`, `spellcheck` and `writingsuggestions` attributes print `true`/`false` as `="true"`/`="false"`; previously they were boolean attributes rendered as a bare name or omitted (ca3f4924)
- **Changed** `hidden` and `popover` attributes keep a non-empty value such as `hidden="until-found"` and a falsy value such as `'0'` omits them; previously they were plain boolean attributes (ca3f4924)
- **Changed** a `<script>` whose `type` is not a plain MIME type of a data block (such as `text/javascript1.5`) is JS-escaped; previously such content was escaped as text (97167859)
- **Changed** the body of an arrow function `fn() => ...` in a tag expression extends over `and`, `or` and `xor` as in PHP; previously it ended before them; wrap the function in parentheses to keep the previous meaning (0397eeac)

## 3.1.6

- **Deprecated** `{first}`, `{last}` and `{sep}` used outside `{foreach}`: put them inside a `{foreach}`; using them outside triggers E_USER_DEPRECATED at compile time (707d23d5)
- **Changed** `Latte\Compiler\Tag::closestTag()` matches subclasses of the given node classes; previously only an exact class name matched (b596015b)
- **Changed** the sandbox policy rules are part of the template cache key; a custom `Latte\Policy` whose rules vary at runtime must implement `Latte\Sandbox\SecurityPolicy::getCacheSignature()`, otherwise a template compiled under one rule set is reused under another (9533252c)
- **Changed** an n:attribute name has only a leading `inner-` or `tag-` prefix stripped; previously the substring was removed wherever it occurred, mangling names such as `n:my-tag-attr` (f431ae0a)
- **Changed** `xlink:href` in inline SVG is treated as a URL attribute and sanitized like `href`; previously its value was printed unsanitized (2181bc91)
- **Changed** a `<script>` whose `type` attribute has surrounding whitespace is recognized as JavaScript and its content is JS-escaped; previously it was treated as raw text (37d88b60)
- **Changed** `{capture}` whose content passes through a filter keeps its HTML-ness in HTML context and is not escaped again; previously the filter result was escaped a second time (ef4c009a)
- **Changed** `|date` applied to a `\DateInterval` without an explicit format throws `Latte\RuntimeException`; previously it printed the format string literally (57dde786)
- **Changed** `|indent` inserts `$` and `\` from the indentation string literally; previously they were interpreted as regular expression backreferences (f1ba38b1)
- **Changed** `|reverse` keeps all elements of an iterator with duplicate keys; previously such elements collapsed into one (088a8211)
- **Changed** `|column` keeps all elements of an iterable with duplicate keys; previously such elements collapsed into one (088a8211)

## 3.1.5

- **Signature** `Latte\Compiler\Nodes\Html\ElementNode::__construct()`: an `$end` parameter was inserted before `$parent`; affects positional construction (b993c379)
- **Signature** the second parameter of `Latte\Compiler\Nodes\Php\Scalar\StringNode::parse()`, `Latte\Compiler\Nodes\Php\Scalar\IntegerNode::parse()`, `Latte\Compiler\Nodes\Php\Scalar\FloatNode::parse()` and `Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode::parse()` was renamed to `$start` and an optional `$end` was added; affects callers using the named argument `position:` (b993c379)
- **Changed** `Latte\Compiler\Nodes\FragmentNode::append()` no longer derives the position from the first appended child; call `Latte\Compiler\Nodes\FragmentNode::updateExtent()` once the tree is built (b993c379)
- **Changed** `Latte\Compiler\NodeTraverser::traverse()` throws `\LogicException` when a visitor replaces or removes a child invalidly; previously a `\TypeError` (971ee567)
- **Changed** `|spaceless` is a streaming HTML-aware minifier: quoted attribute values are not collapsed, word separation around inline tags is preserved, text mode keeps newlines and the region is trimmed (d3cd2b24)
- **Changed** `|strip` is an obsolete alias of `|spaceless` and shares its new behavior; previously it was a simple regular expression collapse (d3cd2b24)
- **Changed** `{spaceless}` uses the same minifier and a nested `{spaceless}` is ignored; previously nested blocks collided through shared static state (d3cd2b24)
- **Changed** `{spaceless}` inside a raw text element or inside an attribute throws `Latte\CompileException`; previously it silently produced wrong output (d3cd2b24)
- **Changed** `{contentType}` inside `<script>` really switches the escaping context; previously it was a no-op there (43e1778a)
- **Changed** `{include parent}` and `{include this}` inside a dynamically named block resolve to the enclosing dynamic block at runtime; previously they failed to compile or bound to an outer static block (efc190f1)

## 3.1.4

- **Removed** `Latte\Essential\Nodes\FirstLastSepNode::$elseLine`: read the position of `{else}` from `Latte\Compiler\Nodes\StatementNode::$tagRanges` (d56a3d4c)
- **Removed** `Latte\Essential\Nodes\ForeachNode::$elseLine`: read the position of `{else}` from `Latte\Compiler\Nodes\StatementNode::$tagRanges` (d56a3d4c)
- **Removed** `Latte\Essential\Nodes\IfChangedNode::$elseLine`: read the position of `{else}` from `Latte\Compiler\Nodes\StatementNode::$tagRanges` (d56a3d4c)
- **Signature** `Latte\Essential\Nodes\SwitchNode::$cases`: each case is a pair of condition and body, the tag position was removed from the tuple and is in `Latte\Compiler\Nodes\StatementNode::$tagRanges` (d56a3d4c)
- **Signature** `|limit`: the second argument, the offset, was removed; use `|slice` to skip elements (babf8cae)

## 3.1.2

- **Deprecated** `Latte\Engine::setTempDirectory()`: use `Latte\Engine::setCacheDirectory()`; the old name is silently deprecated (alias since 3.0.26) (72136748)
- **Deprecated** `Latte\Engine::setStrictParsing()`: call `Latte\Engine::setFeature()` with `Latte\Feature::StrictParsing`; the old name is silently deprecated (16904da4)
- **Deprecated** `Latte\Engine::isStrictParsing()`: call `Latte\Engine::hasFeature()` with `Latte\Feature::StrictParsing`; the old name is silently deprecated (16904da4)
- **Deprecated** `Latte\Engine::setStrictTypes()`: call `Latte\Engine::setFeature()` with `Latte\Feature::StrictTypes`; the old name is silently deprecated (16904da4)
- **Deprecated** `Latte\Engine::setMigrationWarnings()`: call `Latte\Engine::setFeature()` with `Latte\Feature::MigrationWarnings`; the old name is silently deprecated (16904da4)
- **Signature** `Latte\Compiler\TokenStream::peek()` returns a non-nullable `Latte\Compiler\Token` and throws `Latte\CompileException` past the end of the stream; use `Latte\Compiler\TokenStream::tryPeek()` for the previous behavior (3a5320ec)
- **Signature** `Latte\Runtime\Html::__construct()` accepts only `string|Stringable|null`; previously any value (10a251ce)
- **Signature** `|replace`: the subject accepts only a string, no longer an array (10a251ce)
- **Signature** native types were added to the string filters `|capitalize`, `|firstLower`, `|firstUpper`, `|lower`, `|upper`, `|padLeft`, `|padRight`, `|repeat`, `|stripHtml` and `|stripTags`: they accept only `string|Stringable|null`, so other values now raise a `\TypeError` (10a251ce)
- **Changed** a custom template function used with the first-class callable syntax throws `Latte\CompileException`; previously it compiled silently (797d6a25)

## 3.1.1

- **Replaced** `Latte\Compiler\Nodes\Php\Expression\ErrorSuppressNode` with `Latte\Compiler\Nodes\Php\Expression\UnaryOpNode`: the `@` operator is a unary operation node, check `$node instanceof UnaryOpNode && $node->operator === '@'` (30c2bfc4)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\NotNode` with `Latte\Compiler\Nodes\Php\Expression\UnaryOpNode`: the `!` operator is a unary operation node, check the operator (30c2bfc4)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\FunctionCallableNode` with `Latte\Compiler\Nodes\Php\Expression\FunctionCallNode`: the first-class callable syntax is a call node whose first argument is a `Latte\Compiler\Nodes\Php\VariadicPlaceholderNode`, detected by `isPartialFunction()` (eb0d4653)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\MethodCallableNode` with `Latte\Compiler\Nodes\Php\Expression\MethodCallNode` and its `isPartialFunction()` (eb0d4653)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallableNode` with `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode` and its `isPartialFunction()` (eb0d4653)
- **Replaced** `Latte\Sandbox\Nodes\FunctionCallableNode` with `Latte\Sandbox\Nodes\FunctionCallNode` and its `isPartialFunction()` (eb0d4653)
- **Replaced** `Latte\Sandbox\Nodes\MethodCallableNode` with `Latte\Sandbox\Nodes\MethodCallNode` and its `isPartialFunction()` (eb0d4653)
- **Replaced** `Latte\Sandbox\Nodes\StaticMethodCallableNode` with `Latte\Sandbox\Nodes\StaticMethodCallNode` and its `isPartialFunction()` (eb0d4653)
- **Deprecated** `Latte\Compiler\PrintContext::infixOp()`: use `Latte\Compiler\PrintContext::parenthesize()` for the left and the right operand and put the operator between them, the node must implement `Latte\Compiler\Nodes\Php\OperatorNode`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer (b1b487ff)
- **Deprecated** `Latte\Compiler\PrintContext::prefixOp()`: use `Latte\Compiler\PrintContext::parenthesize()`, the node must implement `Latte\Compiler\Nodes\Php\OperatorNode`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer (b1b487ff)
- **Deprecated** `Latte\Compiler\PrintContext::postfixOp()`: use `Latte\Compiler\PrintContext::parenthesize()`, the node must implement `Latte\Compiler\Nodes\Php\OperatorNode`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer (b1b487ff)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\FunctionCallNode::$args` may contain a `Latte\Compiler\Nodes\Php\VariadicPlaceholderNode` besides argument nodes (eb0d4653)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\MethodCallNode::$args` may contain a `Latte\Compiler\Nodes\Php\VariadicPlaceholderNode` besides argument nodes (eb0d4653)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode::$args` may contain a `Latte\Compiler\Nodes\Php\VariadicPlaceholderNode` besides argument nodes (eb0d4653)
- **Changed** `Latte\Tools\Linter` registers `Latte\Tools\LinterExtension`, which reports unknown filters, functions, classes, static methods, constants and static properties with E_USER_WARNING (4e4a07e5)
- **Changed** `Latte\Tools\Linter::scanDirectory()` skips the directories `.*`, `*.tmp`, `temp`, `vendor` and `node_modules`; change `Latte\Tools\Linter::$excludedDirs` to scan them again (b2babcab)

## 3.1.0

- **Removed** `Latte\Compiler\ExpressionBuilder` (deprecated since 3.0.12): build the nodes directly (41c1904b)
- **Removed** `Latte\Loader::isExpired()` (deprecated since 3.0.16): expiration is derived from the cache signature, custom loaders no longer implement it (f5183e6e)
- **Removed** `Latte\Runtime\Template::Source` (4e3a0909)
- **Removed** `Latte\Runtime\Filters::$dateFormat` (marked @internal): pass the format to `|date` or register your own filter (41c1904b)
- **Removed** `Latte\Compiler\Nodes\Html\ElementNode::$tagNode` (623676e1)
- **Removed** `Latte\Compiler\Nodes\Php\Expression\ArrayItemNode` (alias since 3.0.6): use `Latte\Compiler\Nodes\Php\ArrayItemNode` (41c1904b)
- **Removed** `Latte\Compiler\Nodes\Php\Expression\StaticCallNode` (alias since 3.0.12): use `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode` (41c1904b)
- **Removed** `Latte\Compiler\Nodes\Php\Expression\StaticCallableNode` (alias since 3.0.12): use `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallableNode` (41c1904b)
- **Removed** the `@filter` annotation on a method of a template parameter object: use `#[Latte\Attributes\TemplateFilter]` (41c1904b)
- **Removed** the `@function` annotation on a method of a template parameter object: use `#[Latte\Attributes\TemplateFunction]` (41c1904b)
- **Removed** `|escape`: Latte escapes automatically, using the filter throws `Latte\CompileException` (b7a754a1)
- **Renamed** `Latte\Compiler\Tag::$nAttributeNode` → `Latte\Compiler\Tag::$nAttribute`; the old name was removed (e760a428)
- **Replaced** `Latte\Compiler\Nodes\Html\ElementNode::$variableName` with `Latte\Compiler\Nodes\Html\ElementNode::$dynamicTag`: the dynamic tag name is held by the tag node (623676e1)
- **Replaced** `Latte\Compiler\Nodes\Html\ElementNode::$captureTagName` with a test whether `Latte\Compiler\Nodes\Html\ElementNode::$dynamicTag` is set (623676e1)
- **Deprecated** `Latte\Engine::CONTENT_HTML`: use `Latte\ContentType::Html`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::CONTENT_XML`: use `Latte\ContentType::Xml`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::CONTENT_JS`: use `Latte\ContentType::JavaScript`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::CONTENT_CSS`: use `Latte\ContentType::Css`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::CONTENT_ICAL`: use `Latte\ContentType::ICal`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::CONTENT_TEXT`: use `Latte\ContentType::Text`; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer, previously it was silently deprecated (a30019a9)
- **Deprecated** `Latte\Engine::addFilterLoader()`: register each filter with `Latte\Engine::addFilter()`; using it triggers E_USER_DEPRECATED (feb34f04)
- **Deprecated** `Latte\Compiler\TagParser::tryConsumeModifier()`: use `Latte\Compiler\TagParser::tryConsumeTokenBeforeUnquotedString()`; using it triggers E_USER_DEPRECATED (alias since 3.0.5) (29e9bc51)
- **Deprecated** `Latte\Bridges\Tracy\LattePanel::__construct()`: register `Latte\Bridges\Tracy\TracyExtension` instead; using it triggers E_USER_DEPRECATED (deprecated since 3.0.5) (29e9bc51)
- **Deprecated** `Latte\Bridges\Tracy\LattePanel::initialize()`: register `Latte\Bridges\Tracy\TracyExtension` instead; using it triggers E_USER_DEPRECATED on PHP 8.4 and newer (deprecated since 3.0.5) (a30019a9)
- **Deprecated** the variable `$this` in a template compiled without strict parsing: using it triggers E_USER_DEPRECATED (f5177d90)
- **Deprecated** template variables named `$__*` in a template compiled without strict parsing: using them triggers E_USER_DEPRECATED (703e804f)
- **Deprecated** the undefined-safe operators `??->` and `??[]` in a template expression: use `?->` and `?[]`; using them triggers E_USER_DEPRECATED (03ad6b69)
- **Deprecated** an unqualified constant in a template expression, such as `{if FOO}`: write it fully qualified as `\FOO`; using it triggers E_USER_DEPRECATED (35c029fb)
- **Signature** `Latte\Compiler\Nodes\Html\ElementNode::$attributes` is not nullable and is always initialized; drop null checks on it (efa69af6)
- **Signature** `Latte\Compiler\Nodes\Php\FilterNode::printSimple()` is static and takes the whole filter chain as its second parameter (bda7b572)
- **Signature** `Latte\Compiler\Tag::closestTag()` searches only by fully qualified node class name; a tag name such as `foreach` no longer matches (0ec71853)
- **Signature** the public properties of `Latte\Compiler\Tag`, `Latte\Compiler\Token`, `Latte\Compiler\Position`, `Latte\Compiler\Nodes\Html\ElementNode`, `Latte\Compiler\Nodes\AuxiliaryNode` and `Latte\Compiler\Nodes\Php\Expression\AuxiliaryNode` are readonly; writing to them outside the constructor throws `\Error` (d32ffc48)
- **Changed** compiled templates run with `strict_types = 1`; call `Latte\Engine::setStrictTypes()` with false to restore the previous behavior (f6e789a0)
- **Changed** an HTML attribute whose value is null is omitted; previously it rendered as an empty attribute (f8c6e4fd)
- **Changed** a boolean HTML attribute such as `checked` or `disabled` renders bare when the value is truthy and is omitted when falsy; previously it rendered with the value `1` or an empty string (b80b36a5)
- **Changed** a `data-*` attribute renders booleans as `true` and `false` and JSON-encodes arrays and `stdClass`; previously booleans rendered as `1` and an empty string and other values raised an error (80ee0cc0)
- **Changed** an `aria-*` attribute renders booleans as `true` and `false`; previously as `1` and an empty string (ed49fbca)
- **Changed** an ordinary HTML attribute with a boolean, array or object value is omitted and E_USER_WARNING is triggered; previously the value was converted to a string (d05df84e)
- **Changed** the value of the `onclick` and `style` attributes is not escaped as JavaScript or CSS according to the attribute name; write `onclick="alert({$foo})"` to get the previous JSON encoding (01422cb9)
- **Changed** `n:attr` formats values exactly like ordinary HTML attributes; previously arrays were joined with a space and `style` and `on*` values with a semicolon (10641668)
- **Changed** `n:attr` with a non-string or malformed attribute name throws `Latte\RuntimeException`; previously the name was rendered as is (61397d9e)
- **Changed** an attribute whose whole value is a single expression compiles to `Latte\Compiler\Nodes\Html\ExpressionAttributeNode` and is always rendered in double quotes; a traverser matching `Latte\Compiler\Nodes\Html\AttributeNode` must handle it too (95a6df86)
- **Changed** `|noescape` inside an HTML comment throws `Latte\CompileException`; previously the value was escaped anyway (6562bce8)
- **Changed** `|noescape` cannot be allowed in the sandbox by listing it in `Latte\Sandbox\SecurityPolicy::allowFilters()`; it is always forbidden there (9bf04d42)
- **Changed** `Latte\Compiler\Nodes\Php\ModifierNode::printSimple()` no longer consumes the `noescape` filter; a custom node must call `Latte\Compiler\Nodes\Php\ModifierNode::removeFilter()` and set the escape flag itself (9bf04d42)
- **Changed** a custom template function is matched case-sensitively; previously a name differing in case was found and only a warning was reported (969af0bc)
- **Changed** `|escapeHtml` does not entity-encode quotes and understands `Latte\Runtime\HtmlStringable`; previously it was plain `htmlspecialchars()` with `ENT_QUOTES` (9a68cc32)
- **Changed** `Latte\Engine::getCacheFile()` is not consulted when a cached template is loaded or created; overriding it in a subclass has no effect (6d3c3176)

## 3.0.26

- **Renamed** `Latte\Engine::setTempDirectory()` → `Latte\Engine::setCacheDirectory()`; the old name still works as an alias; also in 3.1.2 (d3347869)
- **Changed** the precedence of `!` in compiled expressions was lowered below `instanceof` to match PHP; a template combining `!` with an operator in that range may compile with different parenthesization and a different result (2bad63b1)

## 3.0.25

- **Deprecated** `Latte\Compiler\Nodes\Php\ModifierNode::$check`: it has no effect any more; the property is silently deprecated (cd346802)
- **Signature** `Latte\Compiler\Nodes\Php\ModifierNode::__construct()`: the `$check` parameter was removed and `$position` is the third parameter again (cd346802)
- **Changed** `n:attr` always wraps the value in double quotes and escapes embedded quotes as entities; previously it switched to single quotes when the value contained a double quote (197e523b)

## 3.0.24

- **Changed** `Latte\Compiler\Nodes\Html\ElementNode::getAttribute()` compares attribute names case-sensitively in XML; previously always case-insensitively (35e07b25)
- **Changed** an octal escape sequence above `\377` in a string literal in a tag throws `Latte\CompileException`; previously the value was silently truncated (7ef1f30f)
- **Changed** the check for a print statement placed right before a quote inside `<script>` runs for every script element with a JavaScript type, so it reports more cases than before (dd2ccfc2)

## 3.0.23

- **Removed** `Latte\Essential\Nodes\NTagNode::check()`: the generated code validates the tag change itself (778b1964)
- **Renamed** `Latte\Essential\Nodes\PrintNode` → `Latte\Compiler\Nodes\PrintNode`; the old name still works as an alias (9c086690)

## 3.0.22

- **Signature** `Latte\Compiler\NodeTraverser::traverse()` returns `?Latte\Compiler\Node`; it returns null when the visited node is removed (ffba343e)
- **Changed** `Latte\Loaders\FileLoader::getContent()` throws `Latte\TemplateNotFoundException` for a missing template; previously `Latte\RuntimeException`, which the new exception extends (da3eeeab)
- **Changed** `Latte\Loaders\StringLoader::getContent()` throws `Latte\TemplateNotFoundException` for a missing template; previously `Latte\RuntimeException` (da3eeeab)
- **Changed** `Latte\Loaders\StringLoader::getReferredName()` throws `Latte\TemplateNotFoundException` when used without registered templates; previously `\LogicException`; affects code catching it (da3eeeab)
- **Changed** a dynamic block, template or file name must evaluate to a string, otherwise `\InvalidArgumentException` is thrown; affects `{block}`, `{define}`, `{embed}`, `{include}` and `{import}` (bec003db)

## 3.0.21

- **Changed** `|number` with a pattern and no locale set by `Latte\Engine::setLocale()` throws `Latte\RuntimeException` (92150feb)
- **Changed** `|escapeJs` throws `\JsonException` on an encoding error; previously `Latte\RuntimeException` (2d873a8d)
- **Changed** an HTML attribute value containing a backtick and no other special character no longer gets an extra protective space; the same applies to `n:attr` (670ac08d)

## 3.0.20

- **Changed** `{default}` evaluates its expression only when the variable is not defined; previously it was always evaluated, including its side effects (a396de62)
- **Changed** `|localDate` respects the default PHP time zone; previously it ignored it (e2affb2a)

## 3.0.18

- **Changed** `|number` is not a direct alias of `number_format()`; its second argument may also be an ICU pattern when a locale is set by `Latte\Engine::setLocale()` (bfc0115a)
- **Changed** the parent layout is auto-detected only for the main template; a template rendered as a referred one, for example through `{include}`, no longer gets one (dca85764)

## 3.0.16

- **Deprecated** `Latte\Loader::isExpired()`: expiration is derived from the cache signature; the method is silently deprecated (40a44778)
- **Signature** parameters of `Latte\Engine` were renamed: `$callback` → `$loader` of `addFilterLoader()`, `$value` → `$provider` of `addProvider()`, `$source` → `$template` of `parse()`, `$callback` → `$handler` of `setExceptionHandler()`, `$on` → `$state` of `setAutoRefresh()`, `setSandboxMode()`, `setStrictParsing()` and `setStrictTypes()`; affects callers using named arguments (046594ff)
- **Changed** the argument of `{templatePrint}` is the parent class of the generated template class; previously it was the name of the generated class (11a8cee0)
- **Changed** `Latte\Loaders\FileLoader::isExpired()` always returns false; expiration is detected from a hash of the template content (40a44778)

## 3.0.14

- **Replaced** `Latte\Compiler\Nodes\Php\NameNode::$parts` with `Latte\Compiler\Nodes\Php\NameNode::$name`: one string holding the whole qualified name instead of an array of parts (d5ad2c4c)
- **Signature** `Latte\Compiler\Nodes\Php\NameNode::__construct()` accepts only a string name; previously also an array of parts (d5ad2c4c)
- **Changed** `n:syntax` must not carry a prefix; a prefixed form such as `n:inner-syntax` throws `Latte\CompileException` (3eb2989e)
- **Changed** the content of `<script>` after `{contentType}` is escaped as raw text of the given type; previously it was processed as HTML (1e1939ea)

## 3.0.13

- **Changed** `|breaklines` always outputs `<br>`; previously `<br />` in a template with XML content type (5175d386)

## 3.0.12

- **Removed** `Latte\Strict` (727aa39a)
- **Removed** `Latte\Compiler\Tag::$data` (a4a7f192)
- **Removed** `Latte\Essential\Nodes\JumpNode::$endTag` (d52eb0cd)
- **Renamed** `Latte\Compiler\Nodes\Php\Expression\StaticCallNode` → `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode`; the old name still works as an alias (3b5d7c79)
- **Renamed** `Latte\Compiler\Nodes\Php\Expression\StaticCallableNode` → `Latte\Compiler\Nodes\Php\Expression\StaticMethodCallableNode`; the old name still works as an alias (3b5d7c79)
- **Renamed** `Latte\Sandbox\Nodes\StaticCallNode` → `Latte\Sandbox\Nodes\StaticMethodCallNode`; the old name was removed (3b5d7c79)
- **Renamed** `Latte\Sandbox\Nodes\StaticCallableNode` → `Latte\Sandbox\Nodes\StaticMethodCallableNode`; the old name was removed (3b5d7c79)
- **Replaced** `Latte\RegexpException` with `Latte\CompileException` and `Latte\RuntimeException`: a regular expression error during compilation throws the first, during rendering the second; affects code catching it (05cc3014)
- **Deprecated** `Latte\Compiler\ExpressionBuilder`: build the nodes directly; the class is silently deprecated (a3b1b949)
- **Signature** `Latte\Compiler\Nodes\AuxiliaryNode::__construct()` takes the child nodes as an array instead of variadic arguments (80d835ae)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\AuxiliaryNode::__construct()` takes the child nodes as an array instead of variadic arguments (80d835ae)
- **Signature** `$names` → `$classes` of `Latte\Compiler\Tag::closestTag()`; affects callers using named arguments (dce40945)
- **Signature** `Latte\Compiler\Tag::replaceNAttribute()` accepts only `Latte\Compiler\Nodes\AreaNode`; previously any node (a4a7f192)
- **Changed** `{extends}` or `{layout}` used more than once in a template is not reported; previously it threw `Latte\CompileException` (83856ca5)

## 3.0.10

- **Signature** `Latte\Compiler\TemplateParser::parse()` does not take a lexer; the parser creates it in its constructor (1128db61)
- **Changed** `|noescape` does not switch escaping off completely: quotes in an HTML attribute, the closing tag inside `<script>` and `<style>` and HTML comments are escaped even with it (779be3f8)

## 3.0.9

- **Renamed** `Latte\Engine::VERSION` → `Latte\Engine::Version`; the old name is silently deprecated (7efbcd4e)
- **Renamed** `Latte\Engine::VERSION_ID` → `Latte\Engine::VersionId`; the old name is silently deprecated (7efbcd4e)
- **Replaced** `Latte\Compiler\Position::toWords()` with `Latte\Compiler\Position::__toString()`: convert the position to a string instead of calling the method (035cc474)

## 3.0.8

- **Renamed** `Latte\Compiler\Token::NAMES` → `Latte\Compiler\Token::Names`; the old name was removed (6c35438a)
- **Renamed** `Latte\Compiler\Nodes\Html\ElementNode::$customName` → `Latte\Compiler\Nodes\Html\ElementNode::$variableName`; the old name was removed and the type was narrowed to `Latte\Compiler\Nodes\Php\ExpressionNode` (a7e296b5)
- **Replaced** `Latte\Compiler\Tag::$location` with `Latte\Compiler\Tag::$inHead` and `Latte\Compiler\Tag::$inTag` (3f9fffef)
- **Signature** `Latte\Compiler\Nodes\AuxiliaryNode::__construct()` takes optional child nodes, which are passed to the print closure and traversed (10ca5a3b)
- **Changed** a closing HTML tag may contain only whitespace before the closing bracket; anything else throws `Latte\CompileException` (d37e5704)
- **Changed** `Latte\Compiler\Nodes\Html\ElementNode::is()` compares tag names case-sensitively in XML; previously always case-insensitively as in HTML (3e49e5a2)
- **Changed** `__FILE__`, `__DIR__` and `__LINE__` in a template expression refer to the template source; previously they were printed literally and referred to the compiled file (fab38482)

## 3.0.7

- **Replaced** `Latte\Compiler\Nodes\Html\QuotedValue` with `Latte\Compiler\Nodes\Html\AttributeNode::$quote`: a quoted value is no longer a separate node wrapping the value (fdc1b391)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\ArrayNode::fromArguments()` with `Latte\Compiler\PrintContext::argumentsAsArray()`, which returns the printed string directly (9889ded7)
- **Signature** `Latte\Compiler\Nodes\Html\AttributeNode::__construct()`: a `$quote` parameter was inserted before `$position`; affects positional construction (fdc1b391)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\ArrayNode::$items` is not nullable; a skipped element is an item wrapping a temporary node and `getIterator()` no longer skips nulls (caad6b50)
- **Signature** a destructuring target is a `Latte\Compiler\Nodes\Php\ListNode`: `Latte\Compiler\Nodes\Php\Expression\AssignNode::$var`, `Latte\Essential\Nodes\ForeachNode::$value` and `Latte\Essential\Nodes\IterateWhileNode::$value` may hold it besides an expression node (caad6b50)
- **Changed** a template variable named `$GLOBALS` throws `Latte\CompileException` (b3e244de)
- **Changed** an empty array element in a tag expression throws `Latte\CompileException`; previously it was silently dropped from the printed array (caad6b50)
- **Changed** constructing or printing an assignment, increment, decrement, list or `isset()` node over an expression that cannot be written to throws `Latte\CompileException`; previously it was not validated (b58721df)

## 3.0.6

- **Renamed** `Latte\Compiler\Nodes\Php\Expression\ArrayItemNode` → `Latte\Compiler\Nodes\Php\ArrayItemNode`; the old name still works as an alias (55616904)
- **Renamed** `Latte\Compiler\Nodes\Php\Expression\ClosureUseNode` → `Latte\Compiler\Nodes\Php\ClosureUseNode`; the old name was removed (ca296367)
- **Renamed** `Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode` → `Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode`; the old name was removed (718ac326)
- **Replaced** `Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode` with `Latte\Compiler\Nodes\Php\InterpolatedStringPartNode`, which extends `Latte\Compiler\Node` directly, so a check against `Latte\Compiler\Nodes\Php\ScalarNode` no longer matches it (718ac326)
- **Signature** `Latte\Compiler\Node::getIterator()` is abstract; every custom node class must implement it and return its child nodes or an empty generator (cbd83823)
- **Changed** the precedence of the concatenation operator relative to the arithmetic operators was corrected; a tag expression mixing them may group and evaluate differently (5c7ebdcb)
- **Changed** the list of names reserved in a tag expression: `die`, `readonly`, `mixed`, `void` and `enum` are newly rejected, while among names starting with a double underscore only the listed magic constants are reserved (0eb80062)

## 3.0.5

- **Renamed** `Latte\Compiler\Nodes\Php\Expression\InRangeNode` → `Latte\Compiler\Nodes\Php\Expression\InNode`; the old name was removed (7b3d8cf9)
- **Renamed** `Latte\Essential\Nodes\SkipNode` → `Latte\Essential\Nodes\JumpNode`; the old name was removed (12a1c489)
- **Renamed** `Latte\Compiler\TagParser::tryConsumeModifier()` → `Latte\Compiler\TagParser::tryConsumeTokenBeforeUnquotedString()`; the old name is silently deprecated (48026b39)
- **Changed** `Latte\Compiler\Node::getIterator()` must be implemented by a custom node class; relying on the inherited implementation triggers a runtime warning (06dcf4b4)
- **Changed** `{do}` whose expression starts with a control-flow or declaration keyword throws `Latte\CompileException`; previously it compiled to invalid PHP (500aa78a)
- **Changed** content with the content type `Latte\ContentType::Text` passes through the escape filter, whose default implementation does nothing; previously it was printed with no filter (753195dc)
- **Changed** XML escaping returns the markup of a `Latte\Runtime\HtmlStringable` as is; previously it was escaped (410a80c5)

## 3.0.4

- **Replaced** `Latte\Engine::$probe` (marked @internal) with an extension overriding `Latte\Extension::beforeRender()` and registered by `Latte\Engine::addExtension()` (38039d83)
- **Signature** `Latte\Extension::beforeRender()` takes a `Latte\Runtime\Template` instead of a `Latte\Engine`; the engine is available from the template; affects subclasses overriding it (5df5d1df)
- **Changed** `Latte\Extension::beforeRender()` is called just before the template is rendered; previously in `Latte\Engine::createTemplate()` (49ede9ec)

## 3.0.3

- **Removed** `Latte\Compiler\Nodes\Php\FilterNode::Escape` (4e926915)
- **Removed** `Latte\Compiler\Nodes\Php\FilterNode::NoEscape` (4e926915)
- **Changed** an unterminated tag, comment, attribute value, HTML comment or HTML tag throws `Latte\CompileException`; previously lexing ended silently (c037f00f)

## 3.0.2

- **Replaced** `Latte\Compiler\Nodes\Php\Expression\FunctionCallNode::isFirstClassCallable()` with a check against `Latte\Compiler\Nodes\Php\Expression\FunctionCallableNode`, a separate node for the first-class callable syntax (ed9f9e76)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\MethodCallNode::isFirstClassCallable()` with a check against `Latte\Compiler\Nodes\Php\Expression\MethodCallableNode` (ed9f9e76)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\StaticCallNode::isFirstClassCallable()` with a check against `Latte\Compiler\Nodes\Php\Expression\StaticCallableNode` (ed9f9e76)
- **Replaced** `Latte\Compiler\Nodes\Php\VariadicPlaceholderNode` with the dedicated callable nodes; the first-class callable syntax no longer produces a placeholder inside the arguments of a call node (ed9f9e76)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\NullsafeMethodCallNode` with `Latte\Compiler\Nodes\Php\Expression\MethodCallNode` and its `$nullsafe` flag (e6723a12)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\NullsafePropertyFetchNode` with `Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode` and its `$nullsafe` flag (e6723a12)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\UndefinedsafeMethodCallNode` with a nullsafe `Latte\Compiler\Nodes\Php\Expression\MethodCallNode` over a binary operation node combining the object with null (f165728b)
- **Replaced** `Latte\Compiler\Nodes\Php\Expression\UndefinedsafePropertyFetchNode` with a nullsafe `Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode` over a binary operation node combining the object with null (f165728b)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\MethodCallNode::__construct()`: a `$nullsafe` parameter was inserted before `$position`; affects positional construction (e6723a12)
- **Signature** `Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode::__construct()`: a `$nullsafe` parameter was inserted before `$position`; affects positional construction (e6723a12)
- **Changed** an unquoted argument of `{_...}` is parsed as a literal string; previously as an expression, so a bare word resolved as a constant (54e1f532)

## 3.0.1

- **Signature** `Latte\Compiler\Nodes\Php\ModifierNode::__construct()`: a `$check` parameter was inserted before `$position`; affects positional construction (534c1aa8)
- **Signature** `Latte\Compiler\TagParser::parseType()` returns a `Latte\Compiler\Nodes\Php\SuperiorTypeNode` instead of a string; read its type or print it to get the text (a79f09a7)
- **Signature** `Latte\Essential\Nodes\ParametersNode::$parameters` holds `Latte\Compiler\Nodes\Php\ParameterNode` items with a variable, a default and a type; previously assignment nodes (a79f09a7)
- **Signature** `$dir` → `$path` of `Latte\Tools\Linter::scanDirectory()`; affects callers using named arguments (2858ca5f)
