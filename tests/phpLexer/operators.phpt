<?php

// operators

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a++;
	$b--;
	$a << 2;
	$b >> 2;
	1 + 2;
	$b - 2;
	$a * $b;
	$b % 2;

	$c += $b;
	$b -= $a;
	$a *= 2;
	$d /= 10.50;
	$a %= 10.50;
	$b &= $c;
	$c |= 1;
	$d ^= 5;
	$a >>= 1;
	$b <<= 2;
	$d .= "hello world";

	$a == 0;
	$a === 2;
	$a >= 10;
	$a <= 20;
	$a != 1;
	$a <> 1;
	$a !== 1;

	$a = 1 and 024;
	$b or 0X1E;
	$a xor $b;
	$b && 2;
	$b || 1;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Variable    '$a'
#1:3   Php_Inc         '++'
#1:5   ';'
#1:6   Php_Whitespace  '\n'
#2:1   Php_Variable    '$b'
#2:3   Php_Dec         '--'
#2:5   ';'
#2:6   Php_Whitespace  '\n'
#3:1   Php_Variable    '$a'
#3:3   Php_Whitespace  ' '
#3:4   Php_Sl          '<<'
#3:6   Php_Whitespace  ' '
#3:7   Php_Integer     '2'
#3:8   ';'
#3:9   Php_Whitespace  '\n'
#4:1   Php_Variable    '$b'
#4:3   Php_Whitespace  ' '
#4:4   Php_Sr          '>>'
#4:6   Php_Whitespace  ' '
#4:7   Php_Integer     '2'
#4:8   ';'
#4:9   Php_Whitespace  '\n'
#5:1   Php_Integer     '1'
#5:2   Php_Whitespace  ' '
#5:3   '+'
#5:4   Php_Whitespace  ' '
#5:5   Php_Integer     '2'
#5:6   ';'
#5:7   Php_Whitespace  '\n'
#6:1   Php_Variable    '$b'
#6:3   Php_Whitespace  ' '
#6:4   '-'
#6:5   Php_Whitespace  ' '
#6:6   Php_Integer     '2'
#6:7   ';'
#6:8   Php_Whitespace  '\n'
#7:1   Php_Variable    '$a'
#7:3   Php_Whitespace  ' '
#7:4   '*'
#7:5   Php_Whitespace  ' '
#7:6   Php_Variable    '$b'
#7:8   ';'
#7:9   Php_Whitespace  '\n'
#8:1   Php_Variable    '$b'
#8:3   Php_Whitespace  ' '
#8:4   '%'
#8:5   Php_Whitespace  ' '
#8:6   Php_Integer     '2'
#8:7   ';'
#8:8   Php_Whitespace  '\n\n'
#10:1  Php_Variable    '$c'
#10:3  Php_Whitespace  ' '
#10:4  Php_PlusEqual   '+='
#10:6  Php_Whitespace  ' '
#10:7  Php_Variable    '$b'
#10:9  ';'
#10:10 Php_Whitespace  '\n'
#11:1  Php_Variable    '$b'
#11:3  Php_Whitespace  ' '
#11:4  Php_MinusEqual  '-='
#11:6  Php_Whitespace  ' '
#11:7  Php_Variable    '$a'
#11:9  ';'
#11:10 Php_Whitespace  '\n'
#12:1  Php_Variable    '$a'
#12:3  Php_Whitespace  ' '
#12:4  Php_MulEqual    '*='
#12:6  Php_Whitespace  ' '
#12:7  Php_Integer     '2'
#12:8  ';'
#12:9  Php_Whitespace  '\n'
#13:1  Php_Variable    '$d'
#13:3  Php_Whitespace  ' '
#13:4  Php_DivEqual    '/='
#13:6  Php_Whitespace  ' '
#13:7  Php_Float       '10.50'
#13:12 ';'
#13:13 Php_Whitespace  '\n'
#14:1  Php_Variable    '$a'
#14:3  Php_Whitespace  ' '
#14:4  Php_ModEqual    '%='
#14:6  Php_Whitespace  ' '
#14:7  Php_Float       '10.50'
#14:12 ';'
#14:13 Php_Whitespace  '\n'
#15:1  Php_Variable    '$b'
#15:3  Php_Whitespace  ' '
#15:4  Php_AndEqual    '&='
#15:6  Php_Whitespace  ' '
#15:7  Php_Variable    '$c'
#15:9  ';'
#15:10 Php_Whitespace  '\n'
#16:1  Php_Variable    '$c'
#16:3  Php_Whitespace  ' '
#16:4  Php_OrEqual     '|='
#16:6  Php_Whitespace  ' '
#16:7  Php_Integer     '1'
#16:8  ';'
#16:9  Php_Whitespace  '\n'
#17:1  Php_Variable    '$d'
#17:3  Php_Whitespace  ' '
#17:4  Php_XorEqual    '^='
#17:6  Php_Whitespace  ' '
#17:7  Php_Integer     '5'
#17:8  ';'
#17:9  Php_Whitespace  '\n'
#18:1  Php_Variable    '$a'
#18:3  Php_Whitespace  ' '
#18:4  Php_SrEqual     '>>='
#18:7  Php_Whitespace  ' '
#18:8  Php_Integer     '1'
#18:9  ';'
#18:10 Php_Whitespace  '\n'
#19:1  Php_Variable    '$b'
#19:3  Php_Whitespace  ' '
#19:4  Php_SlEqual     '<<='
#19:7  Php_Whitespace  ' '
#19:8  Php_Integer     '2'
#19:9  ';'
#19:10 Php_Whitespace  '\n'
#20:1  Php_Variable    '$d'
#20:3  Php_Whitespace  ' '
#20:4  Php_ConcatEqual '.='
#20:6  Php_Whitespace  ' '
#20:7  Php_ConstantEncapsedString '\"hello world\"'
#20:20 ';'
#20:21 Php_Whitespace  '\n\n'
#22:1  Php_Variable    '$a'
#22:3  Php_Whitespace  ' '
#22:4  Php_IsEqual     '=='
#22:6  Php_Whitespace  ' '
#22:7  Php_Integer     '0'
#22:8  ';'
#22:9  Php_Whitespace  '\n'
#23:1  Php_Variable    '$a'
#23:3  Php_Whitespace  ' '
#23:4  Php_IsIdentical '==='
#23:7  Php_Whitespace  ' '
#23:8  Php_Integer     '2'
#23:9  ';'
#23:10 Php_Whitespace  '\n'
#24:1  Php_Variable    '$a'
#24:3  Php_Whitespace  ' '
#24:4  Php_IsGreaterOrEqual '>='
#24:6  Php_Whitespace  ' '
#24:7  Php_Integer     '10'
#24:9  ';'
#24:10 Php_Whitespace  '\n'
#25:1  Php_Variable    '$a'
#25:3  Php_Whitespace  ' '
#25:4  Php_IsSmallerOrEqual '<='
#25:6  Php_Whitespace  ' '
#25:7  Php_Integer     '20'
#25:9  ';'
#25:10 Php_Whitespace  '\n'
#26:1  Php_Variable    '$a'
#26:3  Php_Whitespace  ' '
#26:4  Php_IsNotEqual  '!='
#26:6  Php_Whitespace  ' '
#26:7  Php_Integer     '1'
#26:8  ';'
#26:9  Php_Whitespace  '\n'
#27:1  Php_Variable    '$a'
#27:3  Php_Whitespace  ' '
#27:4  Php_IsNotEqual  '<>'
#27:6  Php_Whitespace  ' '
#27:7  Php_Integer     '1'
#27:8  ';'
#27:9  Php_Whitespace  '\n'
#28:1  Php_Variable    '$a'
#28:3  Php_Whitespace  ' '
#28:4  Php_IsNotIdentical '!=='
#28:7  Php_Whitespace  ' '
#28:8  Php_Integer     '1'
#28:9  ';'
#28:10 Php_Whitespace  '\n\n'
#30:1  Php_Variable    '$a'
#30:3  Php_Whitespace  ' '
#30:4  '='
#30:5  Php_Whitespace  ' '
#30:6  Php_Integer     '1'
#30:7  Php_Whitespace  ' '
#30:8  Php_LogicalAnd  'and'
#30:11 Php_Whitespace  ' '
#30:12 Php_Integer     '024'
#30:15 ';'
#30:16 Php_Whitespace  '\n'
#31:1  Php_Variable    '$b'
#31:3  Php_Whitespace  ' '
#31:4  Php_LogicalOr   'or'
#31:6  Php_Whitespace  ' '
#31:7  Php_Integer     '0X1E'
#31:11 ';'
#31:12 Php_Whitespace  '\n'
#32:1  Php_Variable    '$a'
#32:3  Php_Whitespace  ' '
#32:4  Php_LogicalXor  'xor'
#32:7  Php_Whitespace  ' '
#32:8  Php_Variable    '$b'
#32:10 ';'
#32:11 Php_Whitespace  '\n'
#33:1  Php_Variable    '$b'
#33:3  Php_Whitespace  ' '
#33:4  Php_BooleanAnd  '&&'
#33:6  Php_Whitespace  ' '
#33:7  Php_Integer     '2'
#33:8  ';'
#33:9  Php_Whitespace  '\n'
#34:1  Php_Variable    '$b'
#34:3  Php_Whitespace  ' '
#34:4  Php_BooleanOr   '||'
#34:6  Php_Whitespace  ' '
#34:7  Php_Integer     '1'
#34:8  ';'
#34:9  End             ''
