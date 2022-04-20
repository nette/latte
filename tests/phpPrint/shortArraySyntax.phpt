<?php

// Short array syntax

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	[],
	array(1, 2, 3),
	['a' => 'b', 'c' => 'd'],
	[a: 1, b: 2, [c :3, d:hello]]
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
[],
[1, 2, 3],
['a' => 'b', 'c' => 'd'],
['a' => 1, 'b' => 2, ['c' => 3, 'd' => 'hello']]
