<?php

/**
 * Test: Latte\Lexer errors.
 */

declare(strict_types=1);

use Latte\Compiler\Lexer;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new Lexer;
	$lexer->tokenize("\n{a}");
	$lexer->tokenize('');
	Assert::same(1, $lexer->getLine());
});

Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("\xA0\xA0")->getTokens();
}, Latte\CompileException::class, 'Template is not valid UTF-8 stream.');
Assert::same(1, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("žluťoučký\n\xA0\xA0")->getTokens();
}, Latte\CompileException::class, 'Template is not valid UTF-8 stream.');
Assert::same(2, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("{var \n'abc}")->getTokens();
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(1, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("\n{* \n'abc}")->getTokens();
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(2, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize('{')->getTokens();
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(1, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("\n{")->getTokens();
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(2, $lexer->getLine());


Assert::exception(function () use (&$lexer) {
	$lexer = new Lexer;
	$lexer->tokenize("a\x00\x1F\x7Fb")->getTokens();
}, Latte\CompileException::class, 'Template contains control character \x0');
Assert::same(1, $lexer->getLine());
