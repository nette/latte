<?php

// invalid numbers

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	0177777777777777777777787;

	0_10000000000000000000009;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Integer     '0177777777777777777777787'
#1:26  ';'
#1:27  Php_Whitespace  '\n\n'
#3:1   Php_Integer     '0_10000000000000000000009'
#3:26  ';'
#3:27  End             ''
