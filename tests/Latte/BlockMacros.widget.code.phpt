<?php

/**
 * Test: {widget ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{widget "widget1.latte"}
			{block a}
				{widget "widget2.latte"}
					{block a}nested widgets A{/block}
				{/widget}
			{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{block a}widget1-A{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.widget.phtml',
	$latte->compile('main')
);
