<?php

// Calls

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	f($a),
	f(&$a),
	f(...$a),
	f($a, &$b, ...$c),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
f($a),
f(&$a),
f(...$a),
f($a, &$b, ...$c)
