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


test(function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::error(function () use ($info) {
		Filters::stripHtml($info, '');
	}, E_USER_WARNING, 'Filter |stripHtml used with incompatible type TEXT');
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::stripHtml($info, ''));
	Assert::same(Engine::CONTENT_TEXT, $info->contentType);
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', @Filters::stripHtml(clone $info, ''));
	Assert::same('abc', @Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", @Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XHTML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same('', Filters::stripHtml(clone $info, ''));
	Assert::same('abc', Filters::stripHtml(clone $info, 'abc'));
	Assert::same("<  c '", Filters::stripHtml(clone $info, '&lt; <b> c &apos;'));
});
