<?php

/**
 * Test: {extends ...} test V.
 */

declare(strict_types=1);

use Latte\Runtime\Template;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->addFunction('info', fn(Template $template) => basename($template->getReferringTemplate()->getName()) . '/' . $template->getReferenceType());
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/parent.latte'),

	'main' => <<<'XX'

		{extends true ? $ext : "undefined"}

		{block content}
			Content
		{/block}

		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/extends.4.php',
	$latte->compile('main'),
);
Assert::matchFile(
	__DIR__ . '/expected/extends.4.html',
	$latte->renderToString('main', ['ext' => 'parent']),
);
