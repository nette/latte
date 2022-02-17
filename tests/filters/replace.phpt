<?php

/**
 * Test: Latte\Extensions\Filters::replace()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Extensions\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('text', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('', Filters::replace($info, '', ''));
	Assert::same('ab', Filters::replace($info, 'ab', '', ''));
	Assert::same('b', Filters::replace($info, 'ab', 'a'));
	Assert::same('xb', Filters::replace($info, 'ab', 'a', 'x'));
});


test('html', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::replace($info, '', ''));
	Assert::same('ab', Filters::replace($info, 'ab', '', ''));
	Assert::same('b', Filters::replace($info, 'ab', 'a'));
	Assert::same('xb', Filters::replace($info, 'ab', 'a', 'x'));
});


test('array', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('abc', Filters::replace($info, 'abc', []));
	Assert::same('c', Filters::replace($info, 'abc', ['a', 'b']));
	Assert::same('xxc', Filters::replace($info, 'abc', ['a', 'b'], 'x'));
	Assert::same('bac', Filters::replace($info, 'abc', ['a', 'b'], ['b', 'a']));
});


test('assoc', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('ab', Filters::replace($info, 'ba', ['a' => 'b', 'b' => 'a']));
});
