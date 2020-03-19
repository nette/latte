<?php

/**
 * Test: Latte\MacroTokens::parse()
 */

declare(strict_types=1);

use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$tokenizer = new MacroTokens('hello world');
	Assert::same([
		['hello', 0, MacroTokens::T_SYMBOL],
		[' ', 5, MacroTokens::T_WHITESPACE],
		['world', 6, MacroTokens::T_SYMBOL],
	], $tokenizer->tokens);
});


test('', function () {  // UTF-8 Combining character
	$tokenizer = new MacroTokens("\u{0061}\u{0300} $\u{0061}\u{0300}");
	Assert::same([
		["\u{0061}\u{0300}", 0, MacroTokens::T_SYMBOL],
		[' ', 3, MacroTokens::T_WHITESPACE],
		["$\u{0061}\u{0300}", 4, MacroTokens::T_VARIABLE],
	], $tokenizer->tokens);
});


test('', function () {  // UTF-8 emoji
	$tokenizer = new MacroTokens("\u{1F6E1}\u{FE0F}");
	Assert::same([
		["\u{1F6E1}\u{FE0F}", 0, MacroTokens::T_CHAR],
	], $tokenizer->tokens);
});
