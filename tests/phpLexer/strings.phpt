<?php

// strings

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"$a"
	"$b[10]"
	"$a::foo"
	"$b->x"
	"$b?->x"

	"{$a}"
	"{$b[10]}"
	"{$a::foo}"
	"{$b->x }"
	"{$b?->x}"
	"{$b??->x}"

	'{$a}'
	'{ $a}'
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   '\"'
#1:2   Php_Variable    '$a'
#1:4   '\"'
#1:5   Php_Whitespace  '\n'
#2:1   '\"'
#2:2   Php_Variable    '$b'
#2:4   '['
#2:5   Php_NumString   '10'
#2:7   ']'
#2:8   '\"'
#2:9   Php_Whitespace  '\n'
#3:1   '\"'
#3:2   Php_Variable    '$a'
#3:4   Php_EncapsedAndWhitespace '::foo'
#3:9   '\"'
#3:10  Php_Whitespace  '\n'
#4:1   '\"'
#4:2   Php_Variable    '$b'
#4:4   Php_ObjectOperator '->'
#4:6   Php_Identifier  'x'
#4:7   '\"'
#4:8   Php_Whitespace  '\n'
#5:1   '\"'
#5:2   Php_Variable    '$b'
#5:4   Php_NullsafeObjectOperator '?->'
#5:7   Php_Identifier  'x'
#5:8   '\"'
#5:9   Php_Whitespace  '\n\n'
#7:1   '\"'
#7:2   Php_CurlyOpen   '{'
#7:3   Php_Variable    '$a'
#7:5   '}'
#7:6   '\"'
#7:7   Php_Whitespace  '\n'
#8:1   '\"'
#8:2   Php_CurlyOpen   '{'
#8:3   Php_Variable    '$b'
#8:5   '['
#8:6   Php_Integer     '10'
#8:8   ']'
#8:9   '}'
#8:10  '\"'
#8:11  Php_Whitespace  '\n'
#9:1   '\"'
#9:2   Php_CurlyOpen   '{'
#9:3   Php_Variable    '$a'
#9:5   Php_PaamayimNekudotayim '::'
#9:7   Php_Identifier  'foo'
#9:10  '}'
#9:11  '\"'
#9:12  Php_Whitespace  '\n'
#10:1  '\"'
#10:2  Php_CurlyOpen   '{'
#10:3  Php_Variable    '$b'
#10:5  Php_ObjectOperator '->'
#10:7  Php_Identifier  'x'
#10:8  Php_Whitespace  ' '
#10:9  '}'
#10:10 '\"'
#10:11 Php_Whitespace  '\n'
#11:1  '\"'
#11:2  Php_CurlyOpen   '{'
#11:3  Php_Variable    '$b'
#11:5  Php_NullsafeObjectOperator '?->'
#11:8  Php_Identifier  'x'
#11:9  '}'
#11:10 '\"'
#11:11 Php_Whitespace  '\n'
#12:1  '\"'
#12:2  Php_CurlyOpen   '{'
#12:3  Php_Variable    '$b'
#12:5  Php_UndefinedsafeObjectOperator '??->'
#12:9  Php_Identifier  'x'
#12:10 '}'
#12:11 '\"'
#12:12 Php_Whitespace  '\n\n'
#14:1  Php_ConstantEncapsedString ''{$a}''
#14:7  Php_Whitespace  '\n'
#15:1  Php_ConstantEncapsedString ''{ $a}''
#15:8  End             ''
