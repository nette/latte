<?php

// Pretty printer generates least-parentheses output

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	'abc' . 'cde' . 'fgh',
	'abc' . ('cde' . 'fgh'),

	'abc' . 1 + 2 . 'fgh',
	'abc' . (1 + 2) . 'fgh',

	1 * 2 + 3 / 4 % 5 . 6,
	1 * (2 + 3) / (4 % (5 . 6)),

	$a = $b = $c = $d = $f && true,
	($a = $b = $c = $d = $f) && true,
	$a = $b = $c = $d = $f and true,
	$a = $b = $c = $d = ($f and true),

	$a ? $b : $c ? $d : $e ? $f : $g,
	$a ? $b : ($c ? $d : ($e ? $f : $g)),
	$a ? $b ? $c : $d : $f,

	$a ?? $b ?? $c,
	($a ?? $b) ?? $c,
	$a ?? ($b ? $c : $d),
	$a || ($b ?? $c),

	(1 > 0) > (1 < 0),
	++$a + $b,
	$a + $b++,

	$a ** $b ** $c,
	($a ** $b) ** $c,
	-1 ** 2,

	-(-$a),
	+(+$a),
	-(--$a),
	+(++$a),

	/* The following will currently add unnecessary parentheses, because the pretty printer is not aware that assignment */
	/* and incdec only work on variables. */
	!$a = $b,
	++$a ** $b,
	$a ** $b++,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
'abc' . 'cde' . 'fgh',
'abc' . ('cde' . 'fgh'),
'abc' . (1 + 2) . 'fgh',
'abc' . (1 + 2) . 'fgh',
1 * 2 + 3 / 4 % 5 . 6,
1 * (2 + 3) / (4 % (5 . 6)),
$a = $b = $c = $d = $f && true,
($a = $b = $c = $d = $f) && true,
$a = $b = $c = $d = $f and true,
$a = $b = $c = $d = ($f and true),
(($a ? $b : $c) ? $d : $e) ? $f : $g,
$a ? $b : ($c ? $d : ($e ? $f : $g)),
$a ? $b ? $c : $d : $f,
$a ?? $b ?? $c,
($a ?? $b) ?? $c,
$a ?? ($b ? $c : $d),
$a || ($b ?? $c),
(1 > 0) > (1 < 0),
++$a + $b,
$a + $b++,
$a ** $b ** $c,
($a ** $b) ** $c,
-1 ** 2,
-(-$a),
+(+$a),
-(--$a),
+(++$a),
!($a = $b),
(++$a) ** $b,
$a ** ($b++)
