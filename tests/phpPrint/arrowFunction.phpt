<?php

// Arrow function

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	fn($a) => $a,
	fn($x = 42) => $x,
	fn(&$x) => $x,
	fn&($x) => $x,
	fn(): int => $x,
	fn($a, $b) => $a and $b,
	fn($a, $b) => $a && $b,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
fn($a) => $a,
fn($x = 42) => $x,
fn(&$x) => $x,
fn&($x) => $x,
fn(): int => $x,
fn($a, $b) => $a and $b,
fn($a, $b) => $a && $b
