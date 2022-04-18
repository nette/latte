<?php

// isset, empty, unset, exit, die, clone, eval

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	isset($a, $a[$b]),
	empty($a),
	empty('foo'),
	clone $foo
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
isset($a, $a[$b]),
empty($a),
empty('foo'),
clone $foo
