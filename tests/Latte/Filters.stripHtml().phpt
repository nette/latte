<?php

/**
 * Test: Latte\Runtime\Filters::stripHtml()
 */

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

	Assert::same('',  @Filters::stripHtml($info, ''));
	Assert::same('abc',  @Filters::stripHtml($info, 'abc'));
	Assert::same('<  c',  @Filters::stripHtml($info, '&lt; <b> c'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('',  @Filters::stripHtml($info, ''));
	Assert::same('abc',  @Filters::stripHtml($info, 'abc'));
	Assert::same('<  c',  @Filters::stripHtml($info, '&lt; <b> c'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XHTML);
	Assert::same('',  @Filters::stripHtml($info, ''));
	Assert::same('abc',  @Filters::stripHtml($info, 'abc'));
	Assert::same('<  c',  @Filters::stripHtml($info, '&lt; <b> c'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same('',  @Filters::stripHtml($info, ''));
	Assert::same('abc',  @Filters::stripHtml($info, 'abc'));
	Assert::same('<  c',  @Filters::stripHtml($info, '&lt; <b> c'));
});
