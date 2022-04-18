<?php

// Number literals

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	0,
	+0,
	-0,
	0.0,
	-0.0,
	42,
	-42,
	42.0,
	-42.0,
	42.5,
	-42.5,
	1e42,
	-1e42,
	1e1000,
	-1e1000,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
0,
+0,
-0,
0.0,
-0.0,
42,
-42,
42.0,
-42.0,
42.5,
-42.5,
1.0E+42,
-1.0E+42,
\INF,
-\INF
