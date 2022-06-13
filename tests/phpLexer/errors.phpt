<?php

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize(string $code): array
{
	$lexer = new TagLexer;
	return $lexer->tokenize($code);
}


Assert::exception(
	fn() => tokenize("\0 foo"),
	Latte\CompileException::class,
	"Unexpected '\x00' (at column 1)",
);

Assert::exception(
	fn() => tokenize('"$a[]"'),
	Latte\CompileException::class,
	"Unexpected '[]\"' (at column 5)",
);

Assert::exception(
	fn() => tokenize('"aa'),
	Latte\CompileException::class,
	'Unterminated string (at column 4)',
);

Assert::exception(
	fn() => tokenize("'aa"),
	Latte\CompileException::class,
	'Unterminated string (at column 1)',
);

Assert::exception(
	fn() => tokenize('"aa $a'),
	Latte\CompileException::class,
	'Unterminated string (at column 7)',
);

Assert::exception(
	fn() => tokenize('"aa {$a "'),
	Latte\CompileException::class,
	'Unterminated string (at column 10)',
);

Assert::exception(
	fn() => tokenize('"aa $a["'),
	Latte\CompileException::class,
	"Missing ']' (at column 8)",
);

Assert::exception(
	fn() => tokenize('"aa ${a}"'),
	Latte\CompileException::class,
	'Syntax ${...} is not supported (at column 5)',
);

Assert::exception(
	fn() => tokenize("<<<DOC\n"),
	Latte\CompileException::class,
	'Unterminated string (on line 2 at column 1)',
);

Assert::exception(
	fn() => tokenize("<<<'DOC'\n"),
	Latte\CompileException::class,
	'Unterminated NOWDOC (on line 2 at column 1)',
);

Assert::exception(
	fn() => tokenize('/*'),
	Latte\CompileException::class,
	'Unterminated comment (at column 1)',
);
