<?php

declare(strict_types=1);

use Latte\Compiler\TemplateLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new TemplateLexer;
	$lexer->tokenize("\n{a}");
	$lexer->tokenize('');
});

$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\xA0\xA0"),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream (at column 1)',
);


$lexer = new TemplateLexer;
$e = Assert::exception(
	fn() => $lexer->tokenize("žluťoučký\n\xA0\xA0"),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream (on line 2 at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("{var \n'abc}"),
	Latte\CompileException::class,
	'Malformed tag contents (at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\n{* \n'abc}"),
	Latte\CompileException::class,
	'Malformed tag contents (on line 2 at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize('{'),
	Latte\CompileException::class,
	'Malformed tag contents (at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\n{"),
	Latte\CompileException::class,
	'Malformed tag contents (on line 2 at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("a\x00\x1F\x7Fb"),
	Latte\CompileException::class,
	'Template contains control character \x0 (at column 2)',
);
