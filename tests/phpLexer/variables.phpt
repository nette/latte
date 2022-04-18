<?php

// variables

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a[];
	$b[10];
	$a::foo;
	$b->x;
	$b?->x;
	$b??->x;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Variable    '$a'
#1:3   '['
#1:4   ']'
#1:5   ';'
#1:6   Php_Whitespace  '\n'
#2:1   Php_Variable    '$b'
#2:3   '['
#2:4   Php_Integer     '10'
#2:6   ']'
#2:7   ';'
#2:8   Php_Whitespace  '\n'
#3:1   Php_Variable    '$a'
#3:3   Php_PaamayimNekudotayim '::'
#3:5   Php_Identifier  'foo'
#3:8   ';'
#3:9   Php_Whitespace  '\n'
#4:1   Php_Variable    '$b'
#4:3   Php_ObjectOperator '->'
#4:5   Php_Identifier  'x'
#4:6   ';'
#4:7   Php_Whitespace  '\n'
#5:1   Php_Variable    '$b'
#5:3   Php_NullsafeObjectOperator '?->'
#5:6   Php_Identifier  'x'
#5:7   ';'
#5:8   Php_Whitespace  '\n'
#6:1   Php_Variable    '$b'
#6:3   Php_UndefinedsafeObjectOperator '??->'
#6:7   Php_Identifier  'x'
#6:8   ';'
#6:9   End             ''
