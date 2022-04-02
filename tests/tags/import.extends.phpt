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
	'inc' => <<<'XX'

				{extends parent}
				outer text
				{define test}Child {include parent}{/define}

		XX,
	'parent' => <<<'XX'

				outer text
				{define test}Parent{/define}

		XX,
]));

Assert::exception(
	fn() => $latte->renderToString('main'),
	Latte\RuntimeException::class,
	'Imported template cannot use {extends} or {layout}, use {import}',
);
