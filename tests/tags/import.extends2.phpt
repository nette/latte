<?php

/**
 * Test: {import ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

				{extends parent}
				{block main}
					{include test}
				{/block}

		XX,
	'parent' => <<<'XX'

				{import inc}
				{include main}

		XX,
	'inc' => <<<'XX'

				{define test}test block{/define}

		XX,
]));

Assert::match(
	'test block',
	trim($latte->renderToString('main')),
);
