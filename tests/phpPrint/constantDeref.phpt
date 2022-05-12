<?php

// Constant/literal dereferencing

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	FOO[0],
	foo[0],
	x\foo[0],
	FOO::BAR[0],
	'FOO'[0],
	array(FOO)[0],
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
FOO[0],
foo[0],
x\foo[0],
FOO::BAR[0],
'FOO'[0],
[FOO][0]
