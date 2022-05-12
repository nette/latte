<?php

// Uniform variable syntax

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	(function() {})(),
	array('a', 'b')()(),
	A::$b::$c,
	$A::$b[$c](),
	$A::{$b[$c]}(),
	($a->b)(),
	(A::$b)(),
	('a' . 'b')::X,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
(function () {})(),
['a', 'b']()(),
A::$b::$c,
$A::$b[$c](),
$A::{$b[$c]}(),
($a->b)(),
(A::$b)(),
('a' . 'b')::X
