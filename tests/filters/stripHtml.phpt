<?php

/**
 * Test: Latte\Essential\Filters::stripHtml()
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
	'"',
	$latte->renderToString('{="<br>&quot;"|stripHtml}'),
);


test('', function () {
	$info = new FilterInfo(ContentType::Text);
	Assert::exception(
		fn() => Filters::stripHtml($info, ''),
		Latte\RuntimeException::class,
		'Filter |stripHtml used with incompatible type TEXT.',
	);
});


test('', function () {
	$info = new FilterInfo(ContentType::Html);
	Assert::same('', Filters::stripHtml($info, ''));
	Assert::same(ContentType::Text, $info->contentType);
});


test('', function () {
	$info = new FilterInfo(ContentType::Html);
	Assert::same('', @Filters::stripHtml(clone $info, ''));
	Assert::same('abc', @Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", @Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('', function () {
	$info = new FilterInfo(ContentType::Html);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('', function () {
	$info = new FilterInfo(ContentType::Xml);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('invalid UTF-8', function () {
	$info = new FilterInfo(ContentType::Xml);
	Assert::same("foo \u{D800} bar", Filters::stripHtml(clone $info, "foo \u{D800} bar")); // invalid codepoint high surrogates
	Assert::same("foo \xE3\x80\x22 bar", Filters::stripHtml(clone $info, "foo \xE3\x80\x22 bar")); // stripped UTF
});
