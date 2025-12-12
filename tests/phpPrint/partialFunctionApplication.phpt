<?php declare(strict_types=1);

/**
 * Test: Partial Function Application
 * @phpVersion 8.6
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo($a, ?, ...),
	$this->foo($a, ?, ...),
	A::foo($a, ?, ...),

	foo(?),
	foo(foo: ?),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
foo($a, ?, ...),
$this->foo($a, ?, ...),
A::foo($a, ?, ...),
foo(?),
foo(foo: ?)
