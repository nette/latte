<?php declare(strict_types=1);

/**
 * Test: |json attribute modifier
 * |json is not a filter but an attribute-only modifier: as the last modifier of a dynamic
 * HTML attribute it JSON-encodes the value (with smart-quoting). Anywhere else it is an unknown filter.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();


test('encodes value as a JSON attribute with smart-quoting', function () use ($latte) {
	// array -> single quotes (JSON contains ")
	Assert::same(
		'<meta content=\'{"a":1}\'>',
		$latte->renderToString('<meta content={$arr|json}>', ['arr' => ['a' => 1]]),
	);
	// string -> JSON-encoded as "abc"
	Assert::same(
		'<meta content=\'"abc"\'>',
		$latte->renderToString('<meta content={$str|json}>', ['str' => 'abc']),
	);
	// scalars -> double quotes (no " in JSON)
	Assert::same('<meta content="42">', $latte->renderToString('<meta content={$v|json}>', ['v' => 42]));
	Assert::same('<meta content="1.5">', $latte->renderToString('<meta content={$v|json}>', ['v' => 1.5]));
	Assert::same('<meta content="true">', $latte->renderToString('<meta content={$v|json}>', ['v' => true]));
	Assert::same('<meta content="null">', $latte->renderToString('<meta content={$v|json}>', ['v' => null]));
});


test('escapes dangerous characters so the value cannot break out of the attribute', function () use ($latte) {
	// JSON contains " -> single-quoted; & and ' are escaped
	Assert::same(
		'<meta content=\'{"x":"a\"b&amp;c</x>"}\'>',
		$latte->renderToString('<meta content={$v|json}>', ['v' => ['x' => 'a"b&c</x>']]),
	);
	Assert::same(
		'<meta content=\'"a&amp;b&apos;c"\'>',
		$latte->renderToString('<meta content={$v|json}>', ['v' => "a&b'c"]),
	);
});


test('overrides special-attribute handling', function () use ($latte) {
	// class: without |json -> space-joined list; with |json -> JSON array
	Assert::same(
		'<div class="a b"></div>',
		$latte->renderToString('<div class={$cls}></div>', ['cls' => ['a', 'b']]),
	);
	Assert::same(
		'<div class=\'["a","b"]\'></div>',
		$latte->renderToString('<div class={$cls|json}></div>', ['cls' => ['a', 'b']]),
	);
	Assert::same(
		'<div aria-x=\'{"a":1}\'></div>',
		$latte->renderToString('<div aria-x={$arr|json}></div>', ['arr' => ['a' => 1]]),
	);
	// data-* already JSON-encodes arrays; |json additionally forces JSON for scalars
	Assert::same(
		'<div data-x=\'{"a":1}\'></div>',
		$latte->renderToString('<div data-x={$arr|json}></div>', ['arr' => ['a' => 1]]),
	);
	Assert::same(
		'<div data-x=\'"hello"\'></div>',
		$latte->renderToString('<div data-x={$str|json}></div>', ['str' => 'hello']),
	);
});


test('applies only as the last modifier; earlier filters still run', function () use ($latte) {
	Assert::same(
		'<meta content=\'"HELLO"\'>',
		$latte->renderToString('<meta content={$s|upper|json}>', ['s' => 'hello']),
	);
});


test('|json is not a general filter', function () use ($latte) {
	// not the last modifier -> not detected, treated as an unknown filter
	Assert::exception(
		fn() => $latte->renderToString('<meta content={$s|json|upper}>', ['s' => 'hello']),
		LogicException::class,
		"Filter 'json' is not defined%a%",
	);
	// outside an attribute -> unknown filter
	Assert::exception(
		fn() => $latte->renderToString('{$arr|json}', ['arr' => ['a' => 1]]),
		LogicException::class,
		"Filter 'json' is not defined%a%",
	);
	Assert::exception(
		fn() => $latte->renderToString('<script>var x = {$arr|json}</script>', ['arr' => ['a' => 1]]),
		LogicException::class,
		"Filter 'json' is not defined%a%",
	);
});
