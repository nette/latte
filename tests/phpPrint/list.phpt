<?php

// list()

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	list() = $a,
	list($a) = $b,
	list($a, $b, $c) = $d,
	list(, $a) = $b,
	list(, , $a, , $b) = $c,
	list(list($a)) = $b,
	list(, list(, list(, $a), $b)) = $c,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
[] = $a,
[$a] = $b,
[$a, $b, $c] = $d,
[, $a] = $b,
[, , $a, , $b] = $c,
[[$a]] = $b,
[, [, [, $a], $b]] = $c
