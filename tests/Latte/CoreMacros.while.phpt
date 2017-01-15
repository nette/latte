<?php

/**
 * Test: {while}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{while $i++ < 10}
	{$i}
{/while}


{while}
	{$i}
{/while $i++ < 10}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.while.phtml',
	$latte->compile($template)
);
