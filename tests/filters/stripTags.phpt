<?php

/**
 * Test: Latte\Essential\Filters::stripTags()
 */

declare(strict_types=1);

use Latte\ContentType;
use Latte\Engine;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::same(
	'&quot;',
	$latte->renderToString('{="<br>&quot;"|stripTags}'),
);


test('', function () {
	$info = new FilterInfo(ContentType::Text);
	Assert::exception(
		fn() => Filters::stripTags($info, ''),
		Latte\RuntimeException::class,
		'Filter |stripTags used with incompatible type TEXT.',
	);
});


test('', function () {
	$info = new FilterInfo(ContentType::Html);
	Assert::same('', Filters::stripTags($info, ''));
	Assert::same('abc', Filters::stripTags($info, 'abc'));
	Assert::same('&lt;  c', Filters::stripTags($info, '&lt; <b> c'));
});


test('', function () {
	$info = new FilterInfo(ContentType::Xml);
	Assert::same('', Filters::stripTags($info, ''));
	Assert::same('abc', Filters::stripTags($info, 'abc'));
	Assert::same('&lt;  c', Filters::stripTags($info, '&lt; <b> c'));
});
