<?php declare(strict_types=1);

/**
 * Test: no object is encoded to JSON in the sandbox, as it would reveal its public properties.
 */

use Latte\ContentType;
use Latte\Runtime\{TrustedHtml, TrustedJavaScript};
use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Account
{
	public $password = 'secret';
}


class Label
{
	public $password = 'secret';


	public function __toString(): string
	{
		return 'label';
	}
}


class Collection implements Countable, IteratorAggregate
{
	public $password = 'secret';


	public function count(): int
	{
		return 2;
	}


	public function getIterator(): Iterator
	{
		return new ArrayIterator([1, 2]);
	}
}


class Exported implements JsonSerializable
{
	public function jsonSerialize(): mixed
	{
		return 'secret';
	}
}


function createSandbox(?Latte\Policy $policy = null, string $contentType = ContentType::Html): Latte\Engine
{
	$latte = createLatte();
	$latte->setPolicy($policy ?? SecurityPolicy::createSafePolicy());
	$latte->setSandboxMode();
	$latte->setContentType($contentType);
	return $latte;
}


function createPlain(string $contentType = ContentType::Html): Latte\Engine
{
	$latte = createLatte();
	$latte->setContentType($contentType);
	return $latte;
}


$contexts = [
	'<script>var a = {$v};</script>',
	'<script type="application/json">{$v}</script>',
	'<a onclick="f({$v})"></a>',
	'<div data-x={$v}></div>',
	'<div n:attr="data-x: $v"></div>',
];


test('objects are not encoded to JSON', function () use ($contexts) {
	$latte = createSandbox();
	$templates = [
		...$contexts,
		'<div data-x={[$v]}></div>',
		'<div n:attr="data-x: [$v]"></div>',
		'<script>{=[1, [2, [$v]]]}</script>',
		'<div data-x={[1, [2, [$v]]]}></div>',
		'<script>{=[$v]|first}</script>',
	];
	foreach ($templates as $template) {
		Assert::exception(
			fn() => $latte->renderToString($template, ['v' => new Account]),
			Latte\SecurityViolationException::class,
			'Encoding a Account object to JSON is not allowed.',
		);
	}

	Assert::exception(
		fn() => createSandbox(contentType: ContentType::JavaScript)->renderToString('{$v}', ['v' => new Account]),
		Latte\SecurityViolationException::class,
		'Encoding a Account object to JSON is not allowed.',
	);
});


test('objects of any kind are not encoded to JSON', function () {
	$latte = createSandbox();
	Assert::exception(
		fn() => $latte->renderToString('<div data-x={$v}></div>', ['v' => (object) ['password' => 'secret']]),
		Latte\SecurityViolationException::class,
		'Encoding a stdClass object to JSON is not allowed.',
	);
	Assert::exception(
		fn() => $latte->renderToString('<script>{$v}</script>', ['v' => new Exported]),
		Latte\SecurityViolationException::class,
		'Encoding a Exported object to JSON is not allowed.',
	);
	Assert::exception(
		fn() => $latte->renderToString('<script>{$v}</script>', ['v' => new Label]),
		Latte\SecurityViolationException::class,
		'Encoding a Label object to JSON is not allowed.',
	);
	Assert::exception(
		fn() => $latte->renderToString('<script>{=[$v]}</script>', ['v' => new TrustedHtml('<b>')]),
		Latte\SecurityViolationException::class,
		'Encoding a Latte\Runtime\TrustedHtml object to JSON is not allowed.',
	);
});


test('objects are not encoded to JSON even if the policy allows their properties', function () {
	$policy = SecurityPolicy::createSafePolicy();
	$policy->allowProperties(Account::class, $policy::All);
	Assert::exception(
		fn() => createSandbox($policy)->renderToString('<script>{$v}</script>', ['v' => new Account]),
		Latte\SecurityViolationException::class,
		'Encoding a Account object to JSON is not allowed.',
	);
});


test('other values are encoded as outside the sandbox', function () use ($contexts) {
	$sandbox = createSandbox();
	$plain = createPlain();
	$values = ['a"\'</script>&', 1, 1.5, true, false, null, [], [1, 'a'], ['k' => [1, [2, null]]]];
	foreach ($contexts as $template) {
		foreach ($values as $value) {
			Assert::same($plain->renderToString($template, ['v' => $value]), $sandbox->renderToString($template, ['v' => $value]));
		}
	}

	foreach (['<a onclick={$v}></a>', '<div n:attr="onclick: $v"></div>'] as $template) {
		foreach (['a"\'</script>&', 1, 1.5, null] as $value) {
			Assert::same($plain->renderToString($template, ['v' => $value]), $sandbox->renderToString($template, ['v' => $value]));
		}
	}

	$sandbox = createSandbox(contentType: ContentType::JavaScript);
	$plain = createPlain(ContentType::JavaScript);
	foreach ($values as $value) {
		Assert::same($plain->renderToString('{$v}', ['v' => $value]), $sandbox->renderToString('{$v}', ['v' => $value]));
	}
});


test('the check applies to the printed value, not to the input of filters', function () {
	$latte = createSandbox();
	Assert::same('<script>2</script>', $latte->renderToString('<script>{$v|length}</script>', ['v' => new Collection]));
	Assert::same('<script>1</script>', $latte->renderToString('<script>{$v|first}</script>', ['v' => new Collection]));
});


test('trusted content and objects printed as string', function () {
	$sandbox = createSandbox();
	$plain = createPlain();
	$cases = [
		['<script>{$v}</script>', new TrustedJavaScript('alert(1)')],
		['<a onclick="{$v}"></a>', new TrustedJavaScript('alert(1)')],
		['<a onclick={$v}></a>', new TrustedJavaScript('alert(1)')],
		['<div n:attr="onclick: $v"></div>', new TrustedJavaScript('alert(1)')],
		['<div>{$v}</div>', new TrustedHtml('<b>bold</b>')],
		['<div data-x={$v}></div>', new TrustedHtml('<b>bold</b>')],
		['<div>{$v}</div>', new Label],
		['<a title={$v}></a>', new Label],
		['<div n:attr="title: $v"></div>', new Label],
	];
	foreach ($cases as [$template, $value]) {
		Assert::same($plain->renderToString($template, ['v' => $value]), $sandbox->renderToString($template, ['v' => $value]));
	}
});


test('{sandbox} included from a template outside the sandbox', function () {
	$latte = createLatte();
	$latte->setPolicy(SecurityPolicy::createSafePolicy());
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'main' => '{sandbox inc.latte, v: $v}',
		'inc.latte' => '<script>{$v}</script>',
	]));
	Assert::same('<script>[1]</script>', $latte->renderToString('main', ['v' => [1]]));
	Assert::exception(
		fn() => $latte->renderToString('main', ['v' => new Account]),
		Latte\SecurityViolationException::class,
		'Encoding a Account object to JSON is not allowed.',
	);
});
