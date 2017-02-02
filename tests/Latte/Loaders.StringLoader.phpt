<?php

/**
 * Test: StringLoader
 */

declare(strict_types=1);

use Tester\Assert;
use Latte\Loaders\StringLoader;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$loader = new StringLoader;
	Assert::same('content', $loader->getContent('content'));

	Assert::false($loader->isExpired('content', 0));
	Assert::false($loader->isExpired('content', 1));

	Assert::exception(function () use ($loader) {
		$loader->getReferredName('inner', 'referrer');
	}, 'LogicException', "Missing template 'inner'.");
});

test(function () {
	$loader = new StringLoader(['main' => 'maincontent', 'other' => 'othercontent']);
	Assert::same('maincontent', $loader->getContent('main'));
	Assert::same('othercontent', $loader->getContent('other'));

	Assert::false($loader->isExpired('main', 0));
	Assert::false($loader->isExpired('undefined', 1));

	Assert::same('other', $loader->getReferredName('other', 'referrer'));
});
