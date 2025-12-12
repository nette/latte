<?php

// Partial Function Application

declare(strict_types=1);

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
(static fn($__0, ...$__1) => foo($a, $__0, ...$__1)),
(static fn($__0, ...$__1) => $this->foo($a, $__0, ...$__1)),
(static fn($__0, ...$__1) => A::foo($a, $__0, ...$__1)),
(static fn($__0) => foo($__0)),
(static fn($__0) => foo(foo: $__0))
