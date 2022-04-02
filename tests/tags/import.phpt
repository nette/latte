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

				{import "inc"}
				{include test}

		XX,
	'main-dynamic' => <<<'XX'

				{import "i" . "nc"}
				{include test}

		XX,
	'inc' => <<<'XX'

				outer text
				{define test}
					Test block
				{/define}

		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/import.phtml',
	$latte->compile('main'),
);
Assert::match(
	'Test block',
	trim($latte->renderToString('main')),
);

Assert::match(
	'Test block',
	trim($latte->renderToString('main-dynamic')),
);
