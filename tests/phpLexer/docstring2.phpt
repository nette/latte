<?php

// DOC strings

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* nested */
	<<<DOC1
	{$s(<<<DOC2
	DOC2
	)}
	DOC1;
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Comment     '/* nested */'
#1:13  Php_Whitespace  '\n'
#2:1   Php_StartHeredoc '<<<DOC1\n'
#3:1   Php_CurlyOpen   '{'
#3:2   Php_Variable    '$s'
#3:4   '('
#3:5   Php_StartHeredoc '<<<DOC2\n'
#4:1   Php_EndHeredoc  'DOC2'
#4:5   Php_Whitespace  '\n'
#5:1   ')'
#5:2   '}'
#5:3   Php_EncapsedAndWhitespace '\n'
#6:1   Php_EndHeredoc  'DOC1'
#6:5   ';'
#6:6   End             ''
