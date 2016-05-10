<?php

/**
 * Test: Latte\Engine: {includeblock ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(TEMP_DIR);

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.phtml',
	$latte->compile(__DIR__ . '/templates/includeblock.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.html',
	$latte->renderToString(__DIR__ . '/templates/includeblock.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.inc.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . '/templates/includeblock.inc.latte'))
);
