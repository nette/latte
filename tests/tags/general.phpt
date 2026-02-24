<?php declare(strict_types=1);

/**
 * Test: general HTML test.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$params['people'] = ['John', 'Mary', 'Paul', ']]> <!--'];
$params['menu'] = ['about', ['product1', 'product2'], 'contact'];

Assert::matchFile(
	__DIR__ . '/expected/general.php',
	$latte->compile(__DIR__ . '/templates/general.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/general.html',
	$latte->renderToString(
		__DIR__ . '/templates/general.latte',
		$params,
	),
);
