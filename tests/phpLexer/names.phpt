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
	FOO_123
	FOO€

	€€€

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
#8:1   Php_Constant    'FOO_123'
#8:8   Php_Whitespace  '\n'
#9:1   Php_Identifier  'FOO€'
#9:7   Php_Whitespace  '\n\n'
#11:1  Php_Identifier  '€€€'
#11:10 Php_Whitespace  '\n\n'
#13:1  Php_Identifier  'aaa-bbb'
#13:8  Php_Whitespace  '\n'
#14:1  Php_Identifier  'aaa--bbb'
#14:9  Php_Whitespace  '\n'
#15:1  Php_Identifier  'not'
#15:4  Php_Dec         '--'
#15:6  '-'
#15:7  Php_Identifier  'name'
#15:11 Php_Whitespace  '\n'
#16:1  Php_Identifier  'aaa-bbb'
#16:8  '-'
#16:9  Php_Whitespace  '\n'
#17:1  '-'
#17:2  Php_Identifier  'aaa-bbb'
#17:9  End             ''
