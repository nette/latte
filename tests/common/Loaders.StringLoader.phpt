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
	Assert::same('content', $loader->load('content')->content);

	Assert::exception(
		fn() => $loader->getReferredName('inner', 'referrer'),
		Latte\TemplateNotFoundException::class,
		"Missing template 'inner'.",
	);
});

test('predefined template retrieval and reference resolution', function () {
	$loader = new StringLoader(['main' => 'maincontent', 'other' => 'othercontent']);
	Assert::same('maincontent', $loader->load('main')->content);
	Assert::same('othercontent', $loader->load('other')->content);

	Assert::same('other', $loader->getReferredName('other', 'referrer'));
});
