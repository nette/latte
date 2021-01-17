<?php

/**
 * Test: Latte\Runtime\Filters::stripHtml()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::exception(function () use ($info) {
		Filters::stripHtml($info, '');
	}, Latte\RuntimeException::class, 'Filter |stripHtml used with incompatible type TEXT');
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::stripHtml($info, ''));
	Assert::same(Engine::CONTENT_TEXT, $info->contentType);
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', @Filters::stripHtml(clone $info, ''));
	Assert::same('abc', @Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", @Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_XHTML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test('invalid UTF-8', function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same("foo \u{D800} bar", Filters::stripHtml(clone $info, "foo \u{D800} bar")); // invalid codepoint high surrogates
	Assert::same("foo \xE3\x80\x22 bar", Filters::stripHtml(clone $info, "foo \xE3\x80\x22 bar")); // stripped UTF
});
