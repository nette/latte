<?php

/**
 * Test: StringLoader
 */

declare(strict_types=1);

use Latte\Loaders\StringLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('direct content handling and missing template exception', function () {
	$loader = new StringLoader;
	Assert::same('content', $loader->getContent('content'));

	Assert::exception(
		fn() => $loader->getReferredName('inner', 'referrer'),
		LogicException::class,
		"Missing template 'inner'.",
	);
});

test('predefined template retrieval and reference resolution', function () {
	$loader = new StringLoader(['main' => 'maincontent', 'other' => 'othercontent']);
	Assert::same('maincontent', $loader->getContent('main'));
	Assert::same('othercontent', $loader->getContent('other'));

	Assert::same('other', $loader->getReferredName('other', 'referrer'));
});
