<?php

// Unquoted strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	a,
	B,
	AA,
	MD5,

	/* dashes */
	a-b-c,
	a--b--c,

	/* dots */
	a.b,
	a . b,

	/* usage */
	a-b.c-d,
	a.b(),
	foo(aa, bb, cc)
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
'a',
'B',
'AA',
MD5,
'a-b-c',
'a--b--c',
'a.b',
'a' . 'b',
'a-b.c-d',
a.b(),
foo('aa', 'bb', 'cc')
