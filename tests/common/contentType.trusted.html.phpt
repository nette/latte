<?php declare(strict_types=1);

/**
 * Test: HTML objects printed where the escapers deviate from converting content type HTML are deprecated
 */

use Latte\Runtime\{Html, TrustedHtml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class MyHtml extends TrustedHtml
{
}


function throws(string $context, string $class = TrustedHtml::class): string
{
	return "Printing $class in context $context is deprecated, it will throw an exception because content type HTML cannot be converted to it.";
}


function converts(string $context, string $class = TrustedHtml::class): string
{
	return "Printing $class in context $context is deprecated, it will be converted from content type HTML, which changes the output.";
}


$latte = createLatte();
$params = ['h' => new TrustedHtml('<i>x</i>')];


test('the output stays, the deviation is deprecated', function () use ($latte, $params) {
	$cases = [
		['<p style="a: {$h}">', '<p style="a: \&lt;i\&gt;x\&lt;\/i\&gt;">', throws('HTML/ATTR/CSS')],
		['<style>{$h}</style>', '<style>\<i\>x\<\/i\></style>', throws('HTML/RAW/CSS')],
		['{contentType css}{$h}', '\<i\>x\<\/i\>', throws('CSS')],
		['<p style={$h}>', '<p style="x">', throws('HTML/ATTR/CSS')],
		['<p n:attr="style: $h">', '<p style="x">', throws('HTML/ATTR/CSS')],
		['<p onclick={$h}>', '<p onclick="x">', converts('HTML/ATTR/JS')],
		['<p n:attr="onclick: $h">', '<p onclick="x">', converts('HTML/ATTR/JS')],
		['<p {$h}>', '<p &lt;i&gt;x&lt;&#47;i&gt;>', throws('HTML/TAG')],
		['<!doctype {$h}>', '<!doctype &lt;i&gt;x&lt;&#47;i&gt;>', throws('HTML/BOGUS')],
		['<script type="text/foo">{$h}</script>', '<script type="text/foo"><i>x</i></script>', throws('HTML/RAW/TEXT')],
		['{contentType text}{$h}', '<i>x</i>', converts('TEXT')],
		['{contentType calendar}{$h}', '<i>x</i>', throws('ICAL')],
		['{contentType xml}<a {$h}/>', '<a &lt;i&gt;x&lt;&#47;i&gt;/>', throws('XML/TAG')],
	];
	foreach ($cases as [$template, $output, $message]) {
		Assert::error(
			fn() => Assert::same($output, $latte->renderToString($template, $params)),
			E_USER_DEPRECATED,
			$message,
		);
	}
});


test('where the escapers agree with the conversion, nothing is deprecated', function () use ($latte, $params) {
	Assert::same('<i>x</i>', $latte->renderToString('{$h}', $params));
	Assert::same('<textarea><i>x</i></textarea>', $latte->renderToString('<textarea>{$h}</textarea>', $params));
	Assert::same('<p title="x">', $latte->renderToString('<p title="{$h}">', $params));
	Assert::same('<p title="x">', $latte->renderToString('<p title={$h}>', $params));
	Assert::same('<p title="x">', $latte->renderToString('<p n:attr="title: $h">', $params));
	Assert::same('<script>x = "<i>x<\/i>"</script>', $latte->renderToString('<script>x = {$h}</script>', $params));
	Assert::same(
		'<p onclick="f(&quot;&lt;i&gt;x&lt;\/i&gt;&quot;)">',
		$latte->renderToString('<p onclick="f({$h})">', $params),
	);
	Assert::same(
		'<script type="text/html"><i>x</i></script>',
		$latte->renderToString('<script type="text/html">{$h}</script>', $params),
	);
	Assert::same(
		'<iframe srcdoc="&lt;i&gt;x&lt;/i&gt;"></iframe>',
		$latte->renderToString('<iframe srcdoc="{$h}"></iframe>', $params),
	);
	Assert::same(
		'<iframe srcdoc="&lt;i&gt;x&lt;/i&gt;"></iframe>',
		$latte->renderToString('<iframe srcdoc={$h}></iframe>', $params),
	);
	Assert::same('<!-- <i>x</i> -->', $latte->renderToString('<!-- {$h} -->', $params));
	Assert::same('"<i>x<\/i>"', $latte->renderToString('{contentType javascript}{$h}', $params));
});


test('XML escapers print HTML objects as XML, which {capture} relies on', function () use ($latte, $params) {
	Assert::same('<a><i>x</i></a>', $latte->renderToString('{contentType xml}<a>{$h}</a>', $params));
	Assert::same('<a title="x"/>', $latte->renderToString('{contentType xml}<a title="{$h}"/>', $params));
	Assert::same('<a title="x"/>', $latte->renderToString('{contentType xml}<a title={$h}/>', $params));
	Assert::same('<!-- <i>x</i> -->', $latte->renderToString('{contentType xml}<!-- {$h} -->', $params));
});


test('subclass and the deprecated alias', function () use ($latte) {
	Assert::error(
		fn() => Assert::same('<p onclick="x">', $latte->renderToString('<p onclick={$h}>', ['h' => new MyHtml('<i>x</i>')])),
		E_USER_DEPRECATED,
		converts('HTML/ATTR/JS', MyHtml::class),
	);
	Assert::error(
		fn() => Assert::same('<p &lt;i&gt;x&lt;&#47;i&gt;>', $latte->renderToString('<p {$h}>', ['h' => new Html('<i>x</i>')])),
		E_USER_DEPRECATED,
		throws('HTML/TAG'),
	);
});


test('|noescape is not deprecated', function () use ($latte, $params) {
	Assert::same('<p <i>x</i>>', $latte->renderToString('<p {$h|noescape}>', $params));
	Assert::same('<style><i>x</i></style>', $latte->renderToString('<style>{$h|noescape}</style>', $params));
	Assert::same('<i>x</i>', $latte->renderToString('{contentType text}{$h|noescape}', $params));
});


test('Nette\HtmlStringable is deprecated where the escapers treat it like Latte\Runtime\HtmlStringable', function () use ($latte) {
	$params = ['h' => Nette\Utils\Html::el('i')->setText('x')];
	Assert::error(
		fn() => Assert::same('<style>\<i\>x\<\/i\></style>', $latte->renderToString('<style>{$h}</style>', $params)),
		E_USER_DEPRECATED,
		throws('HTML/RAW/CSS', Nette\Utils\Html::class),
	);
	Assert::error(
		fn() => Assert::same('<i>x</i>', $latte->renderToString('{contentType text}{$h}', $params)),
		E_USER_DEPRECATED,
		converts('TEXT', Nette\Utils\Html::class),
	);

	// HtmlHelpers::escapeAttr() and the XML escapers print it as a plain string
	Assert::same('<p title="&lt;i&gt;x&lt;/i&gt;">', $latte->renderToString('<p title="{$h}">', $params));
	Assert::same('<p onclick="&lt;i&gt;x&lt;/i&gt;">', $latte->renderToString('<p onclick={$h}>', $params));
	Assert::same('<p style="&lt;i&gt;x&lt;/i&gt;">', $latte->renderToString('<p style={$h}>', $params));
	Assert::same('<a>&lt;i&gt;x&lt;/i&gt;</a>', $latte->renderToString('{contentType xml}<a>{$h}</a>', $params));
});
