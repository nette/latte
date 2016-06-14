<?php

/**
 * Test: {include file}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(TEMP_DIR);

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.include.phtml',
	$latte->compile(__DIR__ . '/templates/BlockMacros.include.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.include.html',
	$latte->renderToString(
		__DIR__ . '/templates/BlockMacros.include.latte',
		['hello' => '<i>Hello</i>']
	)
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.include.inc1.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/subdir/include1.latte', '/', DIRECTORY_SEPARATOR)))
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.include.inc2.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/subdir/include2.latte', '/', DIRECTORY_SEPARATOR)))
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.include.inc3.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/include3.latte', '/', DIRECTORY_SEPARATOR)))
);
