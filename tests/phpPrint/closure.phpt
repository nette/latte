<?php

// Closures

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$closureWithArgs = function (
	    $arg1,
	    $arg2,
	) {
	    return 'closure body';
	},

	function ($arg1) use ($var1) {
	    return 'closure body';
	},

	function ($arg1) use ($var1) {},

	function ($arg1, $arg2) use ($var1, &$var2) {
	    return 'closure body';
	},
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
$closureWithArgs = fn($arg1, $arg2) => 'closure body',
fn($arg1) => 'closure body',
function ($arg1) use ($var1) {},
function ($arg1, $arg2) use ($var1, &$var2) { return 'closure body'; }
