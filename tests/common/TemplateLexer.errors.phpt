<?php

declare(strict_types=1);

use Latte\Compiler\TemplateLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new TemplateLexer;
	iterator_to_array($lexer->tokenize("\n{a}"));
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
