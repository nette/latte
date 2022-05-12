<?php

// Undefinedsafe operator

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a??->b,
	$a??->b($c),
	$a??->b??->c,
	$a??->b($c)??->d,
	$a??->b($c)(),
	new $a??->b,
	"{$a??->b}",
	"$a??->b",
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
($a ?? null)?->b,
($a ?? null)?->b($c),
(($a ?? null)?->b ?? null)?->c,
(($a ?? null)?->b($c) ?? null)?->d,
(($a ?? null)?->b($c))(),
new (($a ?? null)?->b),
("" . (($a ?? null)?->b) . ""),
("" . (($a ?? null)?->b) . "")
