<?php

// Dereferencing of constants

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	A->length,
	A->length(),
	A[0],
	A[0][1][2],
	x\foo[0],

	A::B[0],
	A::B[0][1][2],
	A::B->length,
	A::B->length(),
	A::B::C,
	A::B::$c,
	A::B::c(),

	$foo::BAR[2][1][0],

	__FUNCTION__[0],
	__FUNCTION__->length,
	__FUNCIONT__->length(),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
A->length,
A->length(),
A[0],
A[0][1][2],
x\foo[0],
A::B[0],
A::B[0][1][2],
A::B->length,
A::B->length(),
A::B::C,
A::B::$c,
A::B::c(),
$foo::BAR[2][1][0],
namespace\__FUNCTION__[0],
namespace\__FUNCTION__->length,
__FUNCIONT__->length()
