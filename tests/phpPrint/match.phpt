<?php

// Match

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	match (1) {
	    0, 1 => 'Foo',
	    /* Comment */
	    2 => 'Bar',
	    default => 'Foo',
	},
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
match (1) {
0, 1 => 'Foo',
2 => 'Bar',
default => 'Foo',
}
