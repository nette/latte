<?php declare(strict_types=1);

/**
 * Test: filters applied to trusted JavaScript, CSS, XML and HTML
 */

use Latte\Runtime\{FilterInfo, TrustedCss, TrustedHtml, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->addFilter('aware', fn(FilterInfo $info, string $s) => $info->contentType . ':' . $s);
$latte->addFilter('toJs', fn(FilterInfo $info, $s) => new TrustedJavaScript((string) $s));

$params = [
	'js' => new TrustedJavaScript('f("</script><script>alert(1)</script>")'),
	'css' => new TrustedCss('a { content: "</style><script>alert(1)</script>" }'),
	'xml' => new TrustedXml('<item>]]></item>'),
	'html' => new TrustedHtml('<b>{{x}}</b>'),
];


test('content-aware filter keeps trusted code in its own context and escapes it elsewhere', function () use ($latte, $params) {
	Assert::same(
		'<script>js:f("<\/script><script>alert(1)<\/script>")</script>',
		$latte->renderToString('<script>{$js|aware}</script>', $params),
	);
	Assert::same(
		'<p>js:f("&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;")</p>',
		$latte->renderToString('<p>{$js|aware}</p>', $params),
	);
	Assert::same(
		'<style>css:a { content: "<\/style><script>alert(1)<\/script>" }</style>',
		$latte->renderToString('<style>{$css|aware}</style>', $params),
	);
	Assert::same(
		'<p>css:a { content: "&lt;/style&gt;&lt;script&gt;alert(1)&lt;/script&gt;" }</p>',
		$latte->renderToString('<p>{$css|aware}</p>', $params),
	);
	Assert::exception(
		fn() => $latte->renderToString('<script>{$css|aware}</script>', $params),
		Latte\RuntimeException::class,
		'Cannot print Latte\Runtime\TrustedCss in context HTML/RAW/JS, content type CSS cannot be converted to it.',
	);
	Assert::same(
		'<x>xml:<item>]]></item></x>',
		$latte->renderToString('{contentType xml}<x>{$xml|aware}</x>', $params),
	);
	Assert::same(
		'<x a="xml:]]&gt;"/>',
		$latte->renderToString('{contentType xml}<x a="{$xml|aware}"/>', $params),
	);
	Assert::same(
		'<p>html:<b>{{x}}</b></p>',
		$latte->renderToString('<p>{$html|aware}</p>', $params),
	);
	Assert::same(
		'<script>"html:<b>{{x}}<\/b>"</script>',
		$latte->renderToString('<script>{$html|aware}</script>', $params),
	);
});


test('core content-aware filters', function () use ($latte, $params) {
	Assert::same(
		'<script>	f("<\/script><script>alert(1)<\/script>")f("<\/script><script>alert(1)<\/script>")</script>',
		$latte->renderToString('<script>{$js|repeat:2|indent}</script>', $params),
	);
	Assert::same(
		'<script>g("<\/script><script>alert(1)<\/script>")</script>',
		$latte->renderToString('<script>{$js|replace:"f", "g"|trim}</script>', $params),
	);
	Assert::same(
		'<p>f("&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;")</p>',
		$latte->renderToString('<p>{$js|spaceless}</p>', $params),
	);
	foreach ($params as $value) {
		Assert::equal($value, $latte->invokeFilter('trim', [$value]));
	}

	Assert::exception(
		fn() => $latte->renderToString('{$js|stripHtml}', $params),
		Latte\RuntimeException::class,
		'Filter |stripHtml used with incompatible type JS.',
	);
	Assert::exception(
		fn() => $latte->renderToString('{$css|stripTags}', $params),
		Latte\RuntimeException::class,
		'Filter |stripTags used with incompatible type CSS.',
	);
	Assert::same(
		'<p>]]&gt;</p>',
		$latte->renderToString('<p>{$xml|stripHtml}</p>', $params),
	);
});


test('classic filter gets the object and its result is escaped as text', function () use ($latte, $params) {
	Assert::same(
		'<script>"F(\"<\/SCRIPT><SCRIPT>ALERT(1)<\/SCRIPT>\")"</script>',
		$latte->renderToString('<script>{$js|upper}</script>', $params),
	);
	Assert::same(
		'<style>A \{ CONTENT\: \"\<\/STYLE\>\<SCRIPT\>ALERT\(1\)\<\/SCRIPT\>\" \}</style>',
		$latte->renderToString('<style>{$css|upper}</style>', $params),
	);
	Assert::same(
		'<x>&lt;ITEM&gt;]]&gt;&lt;/ITEM&gt;</x>',
		$latte->renderToString('{contentType xml}<x>{$xml|upper}</x>', $params),
	);
	Assert::same(
		'<p>&lt;B&gt;{<!-- -->{X}}&lt;/B&gt;</p>',
		$latte->renderToString('<p>{$html|upper}</p>', $params),
	);
	Assert::same(
		'<p>f("&lt;…</p>',
		$latte->renderToString('<p>{$js|truncate:8}</p>', $params),
	);
	Assert::exception(
		fn() => $latte->renderToString('{$js|length}', $params),
		TypeError::class,
		'Latte\Essential\Filters::length(): Argument #1 ($val) must be of type Countable|Traversable|array|string, Latte\Runtime\TrustedJavaScript given%a%',
	);
	Assert::exception(
		fn() => $latte->renderToString('{$css|reverse}', $params),
		TypeError::class,
		'Latte\Essential\Filters::reverse(): Argument #1 ($val) must be of type Traversable|array|string, Latte\Runtime\TrustedCss given%a%',
	);
});


test('filter over content gets an object returned by the previous filter', function () use ($latte) {
	$latte->addFilter('type', fn(FilterInfo $info, $s) => $info->contentType . ':' . get_debug_type($s));
	$type = TrustedJavaScript::class;
	Assert::same('js:string', $latte->renderToString('{contentType javascript}{block|toJs|type}f(){/block}'));
	Assert::same("<script>html/raw/js:$type</script>", $latte->renderToString('<script>{block|toJs|type}f(){/block}</script>'));
	Assert::same("html:$type", $latte->renderToString('{block|toJs|type}f(){/block}'));
	Assert::same('<p>html:string</p>', $latte->renderToString('<p>{capture $c|type}<b>x</b>{/capture}{$c}</p>'));
});
