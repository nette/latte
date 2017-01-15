<?php

/**
 * Test: Latte\Runtime\Filters::replace()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('',  Filters::replace($info, '', ''));
	Assert::same('ab',  Filters::replace($info, 'ab', '', ''));
	Assert::same('b',  Filters::replace($info, 'ab', 'a'));
	Assert::same('xb',  Filters::replace($info, 'ab', 'a', 'x'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('',  Filters::replace($info, '', ''));
	Assert::same('ab',  Filters::replace($info, 'ab', '', ''));
	Assert::same('b',  Filters::replace($info, 'ab', 'a'));
	Assert::same('xb',  Filters::replace($info, 'ab', 'a', 'x'));
});
