<?php

// Array spread

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	[$a, $b] = [...$c, ...$d],
	[(expand) $c, 'x'],
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
[$a, $b] = [...$c, ...$d],
[...$c, 'x']
