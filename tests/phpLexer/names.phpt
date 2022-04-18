<?php

// names

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	Foo
	Foo\Bar
	\Foo\Bar
	namespace\Foo
	Foo \ Bar

	FOO
	FOO123

	aaa-bbb
	aaa--bbb
	not---name
	aaa-bbb-
	-aaa-bbb
	XX;

$tokens = (new TagLexer)->tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Identifier  'Foo'
#1:4   Php_Whitespace  '\n'
#2:1   Php_NameQualified 'Foo\\Bar'
#2:8   Php_Whitespace  '\n'
#3:1   Php_NameFullyQualified '\\Foo\\Bar'
#3:9   Php_Whitespace  '\n'
#4:1   Php_NameQualified 'namespace\\Foo'
#4:14  Php_Whitespace  '\n'
#5:1   Php_Identifier  'Foo'
#5:4   Php_Whitespace  ' '
#5:5   Php_NsSeparator '\\'
#5:6   Php_Whitespace  ' '
#5:7   Php_Identifier  'Bar'
#5:10  Php_Whitespace  '\n\n'
#7:1   Php_Constant    'FOO'
#7:4   Php_Whitespace  '\n'
#8:1   Php_Constant    'FOO123'
#8:7   Php_Whitespace  '\n\n'
#10:1  Php_Identifier  'aaa-bbb'
#10:8  Php_Whitespace  '\n'
#11:1  Php_Identifier  'aaa--bbb'
#11:9  Php_Whitespace  '\n'
#12:1  Php_Identifier  'not'
#12:4  Php_Dec         '--'
#12:6  '-'
#12:7  Php_Identifier  'name'
#12:11 Php_Whitespace  '\n'
#13:1  Php_Identifier  'aaa-bbb'
#13:8  '-'
#13:9  Php_Whitespace  '\n'
#14:1  '-'
#14:2  Php_Identifier  'aaa-bbb'
#14:9  End             ''
