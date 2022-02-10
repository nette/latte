<?php

/**
 * Test: Latte\Parser errors.
 */

declare(strict_types=1);

use Latte\Parser;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$parser = new Parser;
	$parser->parse("\n{a}");
	$parser->parse('');
	Assert::same(1, $parser->getLine());
});

Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("\xA0\xA0");
}, Latte\CompileException::class, 'Template is not valid UTF-8 stream.');
Assert::same(1, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("žluťoučký\n\xA0\xA0");
}, Latte\CompileException::class, 'Template is not valid UTF-8 stream.');
Assert::same(2, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("{var \n'abc}");
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(1, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("\n{* \n'abc}");
}, Latte\CompileException::class, 'Malformed tag contents.');
Assert::same(2, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse('{');
}, Latte\CompileException::class, 'Malformed tag.');
Assert::same(1, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("\n{");
}, Latte\CompileException::class, 'Malformed tag.');
Assert::same(2, $parser->getLine());


Assert::exception(function () use (&$parser) {
	$parser = new Parser;
	$parser->parse("a\x00\x1F\x7Fb");
}, Latte\CompileException::class, 'Template contains control character \x0');
Assert::same(1, $parser->getLine());
