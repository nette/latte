<?php

/**
 * Test: Latte\MacroTokens::append()
 */

declare(strict_types=1);

use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('constructor', function () {
	$tokenizer = new MacroTokens('hello world');
	Assert::count(3, $tokenizer->tokens);

	$tokenizer2 = new MacroTokens($tokenizer->tokens);
	Assert::same($tokenizer2->tokens, $tokenizer->tokens);

	$tokenizer3 = new MacroTokens('');
	Assert::count(0, $tokenizer3->tokens);
});


test('append', function () {
	$tokenizer = new MacroTokens('hello ');

	$res = $tokenizer->append('world!');
	Assert::same($tokenizer, $res);
	Assert::same('hello world!', $tokenizer->joinAll());
	Assert::count(4, $tokenizer->tokens);

	$res = $tokenizer->append($tokenizer->tokens[0]);
	Assert::same('hello world!hello', $tokenizer->reset()->joinAll());
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->append(null);
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->append('');
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->append([]);
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->append(false);
	Assert::count(5, $tokenizer->tokens);
});


test('append with position', function () {
	$tokenizer = new MacroTokens('world');

	$res = $tokenizer->append('!', null);
	Assert::same('world!', $tokenizer->joinAll());

	$res = $tokenizer->append('hello', 0);
	Assert::same('helloworld!', $tokenizer->reset()->joinAll());

	$res = $tokenizer->append(' ', 1);
	Assert::same('hello world!', $tokenizer->reset()->joinAll());

	$res = $tokenizer->append('*', -1);
	Assert::same('hello world*!', $tokenizer->reset()->joinAll());
});


test('prepend', function () {
	$tokenizer = new MacroTokens('world!');

	$res = $tokenizer->prepend('hello ');
	Assert::same($tokenizer, $res);
	Assert::same('hello world!', $tokenizer->joinAll());
	Assert::count(4, $tokenizer->tokens);

	$res = $tokenizer->prepend($tokenizer->tokens[2]);
	Assert::same('worldhello world!', $tokenizer->reset()->joinAll());
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->prepend(null);
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->prepend('');
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->prepend([]);
	Assert::count(5, $tokenizer->tokens);

	$res = $tokenizer->prepend(false);
	Assert::count(5, $tokenizer->tokens);
});
