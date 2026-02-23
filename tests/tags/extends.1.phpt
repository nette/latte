<?php declare(strict_types=1);

/**
 * Test: {extends ...} test VI.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => '{$foo}',

	'main' => '{layout "parent"}
{* This should be erased *}
{var $foo = 1}
This should be erased
',
]));

Assert::matchFile(
	__DIR__ . '/expected/extends.1.php',
	$latte->compile('main'),
);
Assert::same(
	'1',
	$latte->renderToString('main'),
);
