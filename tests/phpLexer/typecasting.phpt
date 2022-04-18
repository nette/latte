<?php

// typecasting

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$c = (int )$b + $a;
	$d = ( float)$a + $b;
	$e = (string)$a.(  string )$b;
	if(!(boolean)$b) echo "false";
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Variable    '$c'
#1:3   Php_Whitespace  ' '
#1:4   '='
#1:5   Php_Whitespace  ' '
#1:6   Php_IntCast     '(int )'
#1:12  Php_Variable    '$b'
#1:14  Php_Whitespace  ' '
#1:15  '+'
#1:16  Php_Whitespace  ' '
#1:17  Php_Variable    '$a'
#1:19  ';'
#1:20  Php_Whitespace  '\n'
#2:1   Php_Variable    '$d'
#2:3   Php_Whitespace  ' '
#2:4   '='
#2:5   Php_Whitespace  ' '
#2:6   Php_FloatCast   '( float)'
#2:14  Php_Variable    '$a'
#2:16  Php_Whitespace  ' '
#2:17  '+'
#2:18  Php_Whitespace  ' '
#2:19  Php_Variable    '$b'
#2:21  ';'
#2:22  Php_Whitespace  '\n'
#3:1   Php_Variable    '$e'
#3:3   Php_Whitespace  ' '
#3:4   '='
#3:5   Php_Whitespace  ' '
#3:6   Php_StringCast  '(string)'
#3:14  Php_Variable    '$a'
#3:16  '.'
#3:17  Php_StringCast  '(  string )'
#3:28  Php_Variable    '$b'
#3:30  ';'
#3:31  Php_Whitespace  '\n'
#4:1   Php_Identifier  'if'
#4:3   '('
#4:4   '!'
#4:5   '('
#4:6   Php_Identifier  'boolean'
#4:13  ')'
#4:14  Php_Variable    '$b'
#4:16  ')'
#4:17  Php_Whitespace  ' '
#4:18  Php_Identifier  'echo'
#4:22  Php_Whitespace  ' '
#4:23  Php_ConstantEncapsedString '\"false\"'
#4:30  ';'
#4:31  End             ''
