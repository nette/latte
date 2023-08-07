<?php

// Variadic functions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function ($a, ...$b) { return null; },
	function ($a, &...$b) { return null; },
	function ($a, Type ...$b) { return null; },
	function ($a, Type &...$b) { return null; },
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
fn($a, ...$b) => null,
fn($a, &...$b) => null,
fn($a, Type ...$b) => null,
fn($a, Type &...$b) => null
