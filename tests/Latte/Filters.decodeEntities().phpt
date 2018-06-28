<?php

/**
 * Test: Latte\Runtime\Filters::decodeEntities()
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
		Filters::decodeEntities($info, '');
	}, E_USER_WARNING, 'Filter |decodeEntities used with incompatible type TEXT');

	Assert::same('', @Filters::decodeEntities($info, ''));
	Assert::same('abc', @Filters::decodeEntities($info, 'abc'));
	Assert::same('< <b> – c  ', @Filters::decodeEntities($info, '&lt; <b> &ndash; c &nbsp;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', @Filters::decodeEntities($info, ''));
	Assert::same('abc', @Filters::decodeEntities($info, 'abc'));
	Assert::same('< <b> – c  ', @Filters::decodeEntities($info, '&lt; <b> &ndash; c &nbsp;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XHTML);
	Assert::same('', @Filters::decodeEntities($info, ''));
	Assert::same('abc', @Filters::decodeEntities($info, 'abc'));
	Assert::same('< <b> – c  ', @Filters::decodeEntities($info, '&lt; <b> &ndash; c &nbsp;'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XML);
	Assert::same('', @Filters::decodeEntities($info, ''));
	Assert::same('abc', @Filters::decodeEntities($info, 'abc'));
	Assert::same('< <b> – c  ', @Filters::decodeEntities($info, '&lt; <b> &ndash; c &nbsp;'));
});
