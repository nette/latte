<?php declare(strict_types=1);

/**
 * Test: <textarea> and <title> keep braces intact
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

// elsewhere braces are separated
Assert::same('<p>a {<!-- -->{b}} &lt;c&gt;</p>', $latte->renderToString('<p>{$v}</p>', $params));
Assert::same('<svg><title>a {<!-- -->{b}} &lt;c&gt;</title></svg>', $latte->renderToString('<svg><title>{$v}</title></svg>', $params));


test('filters and blocks', function () use ($latte, $params) {
	Assert::same(
		'<title>a {{b}} &lt;c&gt;</title>',
		$latte->renderToString('<title>{block|stripHtml}<b>{$v}</b>{/block}</title>', $params),
	);
	Assert::same(
		'<title>a {{b}} &lt;c&gt;</title><p>a {<!-- -->{b}} &lt;c&gt;</p>',
		$latte->renderToString('<title>{block bar}{$v}{/block}</title><p>{include bar}</p>', $params),
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
