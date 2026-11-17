<?php declare(strict_types=1);

/**
 * Test: content of <textarea> and <title>
 */

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$params = ['v' => 'a {{b}} <c>'];

Assert::same('<textarea>a {{b}} &lt;c&gt;</textarea>', $latte->renderToString('<textarea>{$v}</textarea>', $params));
Assert::same('<title>a {{b}} &lt;c&gt;</title>', $latte->renderToString('<title>{$v}</title>', $params));
Assert::same('<textarea><b>a {{b}} &lt;c&gt;</b></textarea>', $latte->renderToString('<textarea><b>{$v}</b></textarea>', $params));
Assert::same('<textarea><i>{{x}}</i></textarea>', $latte->renderToString('<textarea>{$v}</textarea>', ['v' => new Html('<i>{{x}}</i>')]));
Assert::same('<title>&lt;/title><script></title>', $latte->renderToString('<title>{$v}</title>', ['v' => new Html('</title><script>')]));
Assert::same(
	'<textarea><i>&lt;/textarea><script></textarea>',
	$latte->renderToString('<textarea>{$v|noescape}</textarea>', ['v' => '<i></textarea><script>']),
);
Assert::same( // the end tag cannot form from adjacent outputs
	'<textarea>&lt;/textarea><script>&lt;/textarea></textarea>',
	$latte->renderToString('<textarea>{$a}{$b}{$c}{$d}</textarea>', [
		'a' => new Html('</text'),
		'b' => new Html('area><script>'),
		'c' => '<',
		'd' => new Html('/textarea>'),
	]),
);

// elsewhere braces are separated
Assert::same('<p>a {<!-- -->{b}} &lt;c&gt;</p>', $latte->renderToString('<p>{$v}</p>', $params));
Assert::same('<svg><title>a {<!-- -->{b}} &lt;c&gt;</title></svg>', $latte->renderToString('<svg><title>{$v}</title></svg>', $params));


test('filters and blocks', function () use ($latte, $params) {
	Assert::same(
		'<title>a {{b}} &lt;c&gt;</title>',
		$latte->renderToString('<title>{block|stripHtml}<b>{$v}</b>{/block}</title>', $params),
	);
	Assert::same(
		'<title>a &amp; b</title>',
		$latte->renderToString('<title>{block|stripTags}<i>{$v}</i>{/block}</title>', ['v' => new Html('<b>a &amp; b</b>')]),
	);
	Assert::same(
		'<title>a {{b}} &lt;c&gt;</title><p>a {<!-- -->{b}} &lt;c&gt;</p>',
		$latte->renderToString('<title>{block bar}{$v}{/block}</title><p>{include bar}</p>', $params),
	);
});


test('block defined there is included elsewhere', function () {
	$latte = createLatte();
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'layout' => '<title>{block title}A &amp; {$v}{/block}</title><meta content="{include title}"><p onclick="f({include title})"><!-- {include title} -->',
	]));
	Assert::same(
		'<title>A &amp; &lt;c&gt; {{d}}</title><meta content="A &amp; &lt;c&gt; &#123;&#123;d}}"><p onclick="f(&quot;A &amp;amp; &amp;lt;c&amp;gt; &#123;\u003C!-- --&gt;&#123;d}}&quot;)"><!-- A &amp; &lt;c&gt; {<!- -  - - >{d}} -->',
		$latte->renderToString('layout', ['v' => '<c> {{d}}']),
	);
});


test('HTML included there', function () use ($latte) {
	Assert::same(
		'<textarea>a &amp; <b>b</b>&lt;/textarea></textarea>',
		$latte->renderToString('{define foo}a &amp; <b>b</b></textarea>{/define}<textarea>{include foo}</textarea>'),
	);
});


test('overridden block', function () use ($params) {
	$latte = createLatte();
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'main' => '{layout lay}{block title}{$v}{/block}',
		'lay' => '<title>{block title}{/block}</title>',
	]));
	Assert::same('<title>a {<!-- -->{b}} &lt;c&gt;</title>', $latte->renderToString('main', $params));
});


test('classic filter hints |stripHtml', function () {
	Assert::exception(
		fn() => createLatte()->renderToString('<title>{block|upper}a{/block}</title>'),
		Latte\RuntimeException::class,
		'Filter |upper is called with incompatible content type HTML/RCDATA, try to prepend |stripHtml.',
	);
});
