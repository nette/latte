<?php

declare(strict_types=1);

use Latte\Compiler\TemplateLexer;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new TemplateLexer;
	$lexer->tokenize("\n{a}");
	$lexer->tokenize('');
	Assert::same(1, $lexer->getLine());
});

$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\xA0\xA0"),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream.',
);
Assert::same(1, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("žluťoučký\n\xA0\xA0"),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream.',
);
Assert::same(2, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("{var \n'abc}"),
	Latte\CompileException::class,
	'Malformed tag contents.',
);
Assert::same(1, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\n{* \n'abc}"),
	Latte\CompileException::class,
	'Malformed tag contents.',
);
Assert::same(2, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize('{'),
	Latte\CompileException::class,
	'Malformed tag.',
);
Assert::same(1, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("\n{"),
	Latte\CompileException::class,
	'Malformed tag.',
);
Assert::same(2, $lexer->getLine());


$lexer = new TemplateLexer;
Assert::exception(
	fn() => $lexer->tokenize("a\x00\x1F\x7Fb"),
	Latte\CompileException::class,
	'Template contains control character \x0',
);
Assert::same(1, $lexer->getLine());
