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

{for $i = 0; $i < 10; $++}
	{$i}
{/for}


{for $i = 0; $i < 10; $++}
	{breakIf true}
	{continueIf true}
	{$i}
{/for}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/for.phtml',
	$latte->compile($template)
);
