<?php

/**
 * Test: breaking blocks
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'XX'
{foreach [1, 0] as $cond}
	{$cond}
	{block|stripHtml|upper}
		before
		{continueIf $cond}
		after
	{/block}
{/foreach}
XX;

Assert::matchFile(
	__DIR__ . '/expected/breaking.phtml',
	$latte->compile($template)
);
Assert::match(
	'	1
		BEFORE
	0
		BEFORE
		AFTER',
	$latte->renderToString($template)
);
