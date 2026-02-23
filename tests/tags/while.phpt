<?php declare(strict_types=1);

/**
 * Test: {while}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

$template = <<<'EOD'

	{while $i++ < 10}
		{$i}
	{/while}


	{while}
		{$i}
	{/while $i++ < 10}


	{while $i++ < 10}
		{breakIf true}
		{continueIf true}
		{$i}
	{/while}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/while.php',
	$latte->compile($template),
);
