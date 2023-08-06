<?php

// Constant fetches

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	A,
	\A,
	A::B,
	A::class,
	$a::B,
	$a::class,
	Foo::{bar()},
	$foo::{bar()},
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
'A',
\A,
A::B,
A::class,
$a::B,
$a::class,
Foo::{bar()},
$foo::{bar()}
