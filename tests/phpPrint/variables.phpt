<?php

// Variables

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a,
	${$a},
	$a->b,
	$a->b(),
	$a->b($c),
	$a->$b(),
	$a->{$b}(),
	$a->$b[$c](),
	$a[$b],
	$a[$b](),
	$a::B,
	$a::$b,
	$a::b(),
	$a::b($c),
	$a::$b(),
	$a::$b[$c],
	$a::$b[$c]($d),
	$a::{$b[$c]}($d),
	$a::{$b->c}(),
	a(),
	$a(),
	$a()[$b],
	$a->b()[$c],
	$a::$b()[$c],
	(new A)->b,
	(new A())->b(),
	(new $a->b)->c,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
$a,
${$a},
$a->b,
$a->b(),
$a->b($c),
$a->{$b}(),
$a->{$b}(),
$a->{$b}[$c](),
$a[$b],
$a[$b](),
$a::B,
$a::$b,
$a::b(),
$a::b($c),
$a::$b(),
$a::$b[$c],
$a::$b[$c]($d),
$a::{$b[$c]}($d),
$a::{$b->c}(),
a(),
$a(),
$a()[$b],
$a->b()[$c],
$a::$b()[$c],
(new A)->b,
(new A)->b(),
(new $a->b)->c
