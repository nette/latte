<?php declare(strict_types=1);

/**
 * Test: {include ... with blocks}
 */

use Latte\Runtime\Template;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->addFunction('info', fn(Template $template) => basename($template->getReferringTemplate()->getName()) . '/' . $template->getReferenceType());
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

		{include (true ? "inc" : "") with blocks}

		{include test}

		XX,

	'inc' => <<<'XX'

		{define test}
			Parent: {info()}
		{/define}

		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/include.with-blocks.php',
	$latte->compile('main'),
);
Assert::matchFile(
	__DIR__ . '/expected/include.with-blocks.html',
	$latte->renderToString('main'),
);
Assert::matchFile(
	__DIR__ . '/expected/include.with-blocks.inc.php',
	$latte->compile('inc'),
);
