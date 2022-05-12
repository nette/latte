<?php

// scalars

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* int */
	$a = 1 + 034; $b = $a + 0x3F;

	/* float */
	$a = 0.23E-2 + 0.43e2 + 0.5;

	/* bool */
	$a = ($b)? true : false;
	$b = ($a)? FALSE : TRUE;

	/* null */
	$b = null | NULL;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Comment     '/* int */'
#1:10  Php_Whitespace  '\n'
#2:1   Php_Variable    '$a'
#2:3   Php_Whitespace  ' '
#2:4   '='
#2:5   Php_Whitespace  ' '
#2:6   Php_Integer     '1'
#2:7   Php_Whitespace  ' '
#2:8   '+'
#2:9   Php_Whitespace  ' '
#2:10  Php_Integer     '034'
#2:13  ';'
#2:14  Php_Whitespace  ' '
#2:15  Php_Variable    '$b'
#2:17  Php_Whitespace  ' '
#2:18  '='
#2:19  Php_Whitespace  ' '
#2:20  Php_Variable    '$a'
#2:22  Php_Whitespace  ' '
#2:23  '+'
#2:24  Php_Whitespace  ' '
#2:25  Php_Integer     '0x3F'
#2:29  ';'
#2:30  Php_Whitespace  '\n\n'
#4:1   Php_Comment     '/* float */'
#4:12  Php_Whitespace  '\n'
#5:1   Php_Variable    '$a'
#5:3   Php_Whitespace  ' '
#5:4   '='
#5:5   Php_Whitespace  ' '
#5:6   Php_Float       '0.23E-2'
#5:13  Php_Whitespace  ' '
#5:14  '+'
#5:15  Php_Whitespace  ' '
#5:16  Php_Float       '0.43e2'
#5:22  Php_Whitespace  ' '
#5:23  '+'
#5:24  Php_Whitespace  ' '
#5:25  Php_Float       '0.5'
#5:28  ';'
#5:29  Php_Whitespace  '\n\n'
#7:1   Php_Comment     '/* bool */'
#7:11  Php_Whitespace  '\n'
#8:1   Php_Variable    '$a'
#8:3   Php_Whitespace  ' '
#8:4   '='
#8:5   Php_Whitespace  ' '
#8:6   '('
#8:7   Php_Variable    '$b'
#8:9   ')'
#8:10  '?'
#8:11  Php_Whitespace  ' '
#8:12  Php_True        'true'
#8:16  Php_Whitespace  ' '
#8:17  ':'
#8:18  Php_Whitespace  ' '
#8:19  Php_False       'false'
#8:24  ';'
#8:25  Php_Whitespace  '\n'
#9:1   Php_Variable    '$b'
#9:3   Php_Whitespace  ' '
#9:4   '='
#9:5   Php_Whitespace  ' '
#9:6   '('
#9:7   Php_Variable    '$a'
#9:9   ')'
#9:10  '?'
#9:11  Php_Whitespace  ' '
#9:12  Php_False       'FALSE'
#9:17  Php_Whitespace  ' '
#9:18  ':'
#9:19  Php_Whitespace  ' '
#9:20  Php_True        'TRUE'
#9:24  ';'
#9:25  Php_Whitespace  '\n\n'
#11:1  Php_Comment     '/* null */'
#11:11 Php_Whitespace  '\n'
#12:1  Php_Variable    '$b'
#12:3  Php_Whitespace  ' '
#12:4  '='
#12:5  Php_Whitespace  ' '
#12:6  Php_Null        'null'
#12:10 Php_Whitespace  ' '
#12:11 '|'
#12:12 Php_Whitespace  ' '
#12:13 Php_Null        'NULL'
#12:17 ';'
#12:18 End             ''
