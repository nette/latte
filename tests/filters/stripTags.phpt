<?php

/**
 * Test: Latte\Runtime\Filters::stripTags()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::same(
	'&quot;',
	$latte->renderToString('{="<br>&quot;"|stripTags}'),
);


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::exception(function () use ($info) {
		Filters::stripTags($info, '');
	}, Latte\RuntimeException::class, 'Filter |stripTags used with incompatible type TEXT.');
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::stripTags($info, ''));
	Assert::same('abc', Filters::stripTags($info, 'abc'));
	Assert::same('&lt;  c', Filters::stripTags($info, '&lt; <b> c'));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same('', Filters::stripTags($info, ''));
	Assert::same('abc', Filters::stripTags($info, 'abc'));
	Assert::same('&lt;  c', Filters::stripTags($info, '&lt; <b> c'));
});
