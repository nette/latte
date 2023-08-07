<?php

// Array definitions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	array(),
	array('a', 'b'),
	array('a', &$b, 'c' => 'd', 'e' => &$f),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
[],
['a', 'b'],
['a', &$b, 'c' => 'd', 'e' => &$f]
