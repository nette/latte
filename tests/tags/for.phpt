<?php

/**
 * Test: {for}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	{for $i = 0; $i < 10; $i++}
		{$i}
	{/for}


	{for ;;}
		{$i}
	{/for}


	{for $i = 0, $a = 1; $i < 10; $i++, $a++}
		{$i}
	{/for}


	{for $i = 0; $i < 10; $i++}
		{breakIf true}
		{continueIf true}
		{$i}
	{/for}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/for.php',
	$latte->compile($template),
);
