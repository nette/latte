<?php

/**
 * Test: {include file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(getTempDir());

Assert::matchFile(
	__DIR__ . '/expected/include.phtml',
	$latte->compile(__DIR__ . '/templates/include.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/include.html',
	$latte->renderToString(
		__DIR__ . '/templates/include.latte',
		['hello' => '<i>Hello</i>'],
	),
);
Assert::matchFile(
	__DIR__ . '/expected/include.inc1.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/subdir/include1.latte', '/', DIRECTORY_SEPARATOR))),
);
Assert::matchFile(
	__DIR__ . '/expected/include.inc2.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/subdir/include2.latte', '/', DIRECTORY_SEPARATOR))),
);
Assert::matchFile(
	__DIR__ . '/expected/include.inc3.phtml',
	file_get_contents($latte->getCacheFile(__DIR__ . strtr('/templates/include3.latte', '/', DIRECTORY_SEPARATOR))),
);
