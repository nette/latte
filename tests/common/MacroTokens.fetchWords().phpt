<?php

/**
 * Test: Latte\MacroTokens::fetchWords()
 */

declare(strict_types=1);

use Latte\Compiler\MacroTokens;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$tokenizer = new MacroTokens('');
	Assert::same([], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('$1d-,a');
	Assert::same(['$1d-'], $tokenizer->fetchWords());
	Assert::same('a', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('"a:":$b" c" ,');
	Assert::same(['"a:"', '$b" c"'], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('a. b');
	Assert::same(['a. b'], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('a . b');
	Assert::same(['a . b'], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('a .b');
	Assert::same(['a .b'], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});


test('', function () {
	$tokenizer = new MacroTokens('a . b:x,');
	Assert::same(['a . b:x'], $tokenizer->fetchWords());
	Assert::same('', $tokenizer->joinAll());
});
