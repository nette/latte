<?php

// Nullsafe operator

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a?->b,
	$a?->b($c),
	$a?->b?->c,
	$a?->b($c)?->d,
	$a?->b($c)(),
	new $a?->b,
	"{$a?->b}",
	"$a?->b",
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
$a?->b,
$a?->b($c),
$a?->b?->c,
$a?->b($c)?->d,
$a?->b($c)(),
new $a?->b,
"{$a?->b}",
"{$a?->b}"
