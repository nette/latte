<?php

/**
 * Test: general n:attributes test.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

$params['people'] = ['John', 'Mary', 'Paul'];

Assert::matchFile(
	__DIR__ . '/expected/general.n-attributes.phtml',
	$latte->compile(__DIR__ . '/templates/n-attributes.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/general.n-attributes.html',
	$latte->renderToString(
		__DIR__ . '/templates/n-attributes.latte',
		$params,
	),
);
