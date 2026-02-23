<?php declare(strict_types=1);

// Parentheses for complex new/instanceof expressions

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	new ('a' . 'b'),
	$x instanceof ('a' . 'b'),
	$x instanceof ($y++),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
new ('a' . 'b'),
$x instanceof ('a' . 'b'),
$x instanceof ($y++)
