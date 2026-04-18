<?php declare(strict_types=1);

/**
 * Test: |json filter
 */

use Latte\Runtime\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class MyHtmlStringable implements Latte\Runtime\HtmlStringable
{
	public function __toString(): string
	{
		return '<b>html</b>';
	}
}


class TestJsonSerializable implements \JsonSerializable
{
	public function jsonSerialize(): array
	{
		return ['key' => 'value'];
	}
}


test('scalars', function () {
	Assert::same('null', Helpers::encodeJson(null));
	Assert::same('true', Helpers::encodeJson(true));
	Assert::same('false', Helpers::encodeJson(false));
	Assert::same('0', Helpers::encodeJson(0));
	Assert::same('42', Helpers::encodeJson(42));
	Assert::same('1.5', Helpers::encodeJson(1.5));
	Assert::same('""', Helpers::encodeJson(''));
	Assert::same('"abc"', Helpers::encodeJson('abc'));
});


test('arrays and objects', function () {
	Assert::same('[]', Helpers::encodeJson([]));
	Assert::same('[1,2,3]', Helpers::encodeJson([1, 2, 3]));
	Assert::same('{"a":1,"b":2}', Helpers::encodeJson(['a' => 1, 'b' => 2]));
	Assert::same('{"a":1}', Helpers::encodeJson((object) ['a' => 1]));
	Assert::same('{"key":"value"}', Helpers::encodeJson(new TestJsonSerializable));
});


test('UTF-8 preserved', function () {
	Assert::same('"čau"', Helpers::encodeJson('čau'));
	Assert::same('"日本"', Helpers::encodeJson('日本'));
});


test('slashes unescaped', function () {
	Assert::same('"a/b"', Helpers::encodeJson('a/b'));
	Assert::same('"http://example.com"', Helpers::encodeJson('http://example.com'));
});


test('dangerous sequences escaped', function () {
	Assert::same('"]]\u003E"', Helpers::encodeJson(']]>'));
	Assert::same('"\u003C!--"', Helpers::encodeJson('<!--'));
	Assert::same('"<\/script>"', Helpers::encodeJson('</script>'));
});


test('HtmlStringable is NOT unwrapped (unlike escapeJs)', function () {
	// object with only private/empty public props serializes to {}
	Assert::same('{}', Helpers::encodeJson(new MyHtmlStringable));
	// escapeJs for comparison unwraps to string
	Assert::same('"<b>html<\/b>"', Helpers::escapeJs(new MyHtmlStringable));
});


test('via Latte: HTML text context', function () {
	// { escaped to &#123;, " kept literal (ENT_NOQUOTES)
	Assert::same(
		'&#123;"a":1}',
		createLatte()->renderToString('{$arr|json}', ['arr' => ['a' => 1]]),
	);
});


test('via Latte: JS context', function () {
	$latte = createLatte();

	// auto-escapeJs wraps |json output as a JS string literal
	Assert::same(
		'<script>var x = "{\"a\":1}";</script>',
		$latte->renderToString('<script>var x = {$arr|json};</script>', ['arr' => ['a' => 1]]),
	);
	// use |noescape to get raw JSON in JS context
	Assert::same(
		'<script>var x = {"a":1};</script>',
		$latte->renderToString('<script>var x = {$arr|json|noescape};</script>', ['arr' => ['a' => 1]]),
	);
});


test('via Latte: HTML attribute context', function () {
	$latte = createLatte();

	// array -> single quotes because JSON contains "
	Assert::same(
		'<meta content=\'{"a":1}\'>',
		$latte->renderToString('<meta content={$arr|json}>', ['arr' => ['a' => 1]]),
	);
	// string -> JSON-encoded as "abc", wrapped in single quotes
	Assert::same(
		'<meta content=\'"abc"\'>',
		$latte->renderToString('<meta content={$str|json}>', ['str' => 'abc']),
	);
	// number -> JSON literal, wrapped in double quotes (no " in JSON)
	Assert::same(
		'<meta content="42">',
		$latte->renderToString('<meta content={$n|json}>', ['n' => 42]),
	);
	// bool -> JSON literal true
	Assert::same(
		'<meta content="true">',
		$latte->renderToString('<meta content={$b|json}>', ['b' => true]),
	);
	// null -> JSON literal null
	Assert::same(
		'<meta content="null">',
		$latte->renderToString('<meta content={$v|json}>', ['v' => null]),
	);
	// float
	Assert::same(
		'<meta content="1.5">',
		$latte->renderToString('<meta content={$v|json}>', ['v' => 1.5]),
	);
});


test('via Latte: |json overrides special-attribute handling', function () {
	$latte = createLatte();

	// class: without |json -> list attribute (space-joined)
	Assert::same(
		'<div class="a b"></div>',
		$latte->renderToString('<div class={$cls}></div>', ['cls' => ['a', 'b']]),
	);
	// class: with |json -> JSON array
	Assert::same(
		'<div class=\'["a","b"]\'></div>',
		$latte->renderToString('<div class={$cls|json}></div>', ['cls' => ['a', 'b']]),
	);
	// aria-*: with |json -> JSON-encoded
	Assert::same(
		'<div aria-x=\'{"a":1}\'></div>',
		$latte->renderToString('<div aria-x={$arr|json}></div>', ['arr' => ['a' => 1]]),
	);
});


test('via Latte: |json in event handler attribute', function () {
	// onclick is JS context, but |json detection fires first -> raw JSON literal
	Assert::same(
		'<div onclick=\'{"a":1}\'></div>',
		createLatte()->renderToString('<div onclick={$arr|json}></div>', ['arr' => ['a' => 1]]),
	);
});


test('via Latte: |json in XML template', function () {
	$latte = createLatte();
	$latte->setContentType(Latte\ContentType::Xml);

	// XML: no compile-time detection, |json runs as regular filter and output is XML-escaped
	Assert::same(
		'<meta content="{&quot;a&quot;:1}"/>',
		$latte->renderToString('<meta content={$arr|json}/>', ['arr' => ['a' => 1]]),
	);
});


test('via Latte: data-* attribute', function () {
	$latte = createLatte();

	// data-x without |json: auto JSON for arrays
	Assert::same(
		'<div data-x=\'{"a":1}\'></div>',
		$latte->renderToString('<div data-x={$arr}></div>', ['arr' => ['a' => 1]]),
	);
	// data-x with |json for array: same result
	Assert::same(
		'<div data-x=\'{"a":1}\'></div>',
		$latte->renderToString('<div data-x={$arr|json}></div>', ['arr' => ['a' => 1]]),
	);
	// data-x without |json: string passthrough
	Assert::same(
		'<div data-x="hello"></div>',
		$latte->renderToString('<div data-x={$str}></div>', ['str' => 'hello']),
	);
	// data-x with |json: string JSON-encoded as "hello"
	Assert::same(
		'<div data-x=\'"hello"\'></div>',
		$latte->renderToString('<div data-x={$str|json}></div>', ['str' => 'hello']),
	);
});


test('via Latte: chain with other filter', function () {
	$latte = createLatte();

	// |json is last -> compile-time detection fires, |upper runs first, then formatJsonAttribute
	Assert::same(
		'<meta content=\'"HELLO"\'>',
		$latte->renderToString('<meta content={$s|upper|json}>', ['s' => 'hello']),
	);
	// |json is NOT last -> detection skipped, both filters run as regular and output is escaped by formatAttribute
	Assert::same(
		'<meta content="&quot;HELLO&quot;">',
		$latte->renderToString('<meta content={$s|json|upper}>', ['s' => 'hello']),
	);
});
