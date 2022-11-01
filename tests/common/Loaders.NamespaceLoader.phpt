<?php

/**
 * Test: StringLoader
 */

declare(strict_types=1);

use Latte\Loaders\StringLoader;
use Latte\Loaders\NamespaceLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$defaultLoader = new StringLoader(['main' => 'defaultcontent']);
	$appLoader = new StringLoader(['main' => 'appcontent']);
	$otherLoader = new StringLoader(['main' => 'othercontent']);

	$loader = new NamespaceLoader([
		'' => $defaultLoader,
		'app' => $appLoader,
		'other' => $otherLoader,
	]);

	Assert::same('defaultcontent', $loader->getContent('main'));
	Assert::same('appcontent', $loader->getContent('app::main'));
	Assert::same('othercontent', $loader->getContent('other::main'));
});
