<?php

/**
 * Test: special cases
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(function () {
			return 1;
		}))%A%',
	$latte->compile('{test(function () { return 1;})}')
);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(function () use ($a) {
			return 1;
		}))%A%',
	$latte->compile('{test(function () use ($a) { return 1;})}')
);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(fn () => 1))%A%',
	$latte->compile('{test(fn () => 1)}')
);

Assert::match(
	"%A%('foo')/ **/('bar')%A%",
	$latte->compile('{(foo)//**/**/(bar)}')
);
