<?php

declare(strict_types=1);

use Latte\Compiler\TemplateLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new TemplateLexer;
	iterator_to_array($lexer->tokenize("\n{a}"));
	iterator_to_array($lexer->tokenize(''));
});

$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("\xA0\xA0"), false),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream (on line 1 at column 1)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("žluťoučký\n\xA0\xA0"), false),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream (on line 2 at column 1)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("a\x00\x1F\x7Fb"), false),
	Latte\CompileException::class,
	'Template contains control character \x0 (on line 1 at column 2)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize(' {')),
	Latte\CompileException::class,
	'Unterminated Latte tag (on line 1 at column 3)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize(" {* \n'abc}")),
	Latte\CompileException::class,
	'Unterminated Latte comment (on line 1 at column 4)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("<a href='xx{* xx *}>")),
	Latte\CompileException::class,
	'Unterminated HTML attribute value (on line 1 at column 10)',
);

Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("<a n:href='xx>")),
	Latte\CompileException::class,
	'Unterminated n:attribute value (on line 1 at column 12)',
);
