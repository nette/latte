<?php

/**
 * Test: Latte\MacroTokens::fetchWord()
 */

declare(strict_types=1);

use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function testWord($expr, $word, $rest = '')
{
	$tokenizer = new MacroTokens($expr);
	Assert::same($word, $tokenizer->fetchWord());
	Assert::same($rest, $tokenizer->joinAll());
}


testWord('', null);
@testWord('$1d-,a', '$1d-', 'a'); // @ missing quotes
testWord('"item\'1""item2"', '"item\'1""item2"');
testWord('(symbol)', '(symbol)');
testWord('($expr)', '($expr)');
testWord('($expr ? (1+2) : [3,4]),x', '($expr ? (1+2) : [3,4])', 'x');
testWord('($expr ? (1+2) : [3,4]) x', '($expr ? (1+2) : [3,4])', 'x');
testWord('$expr instanceof stdClass ? : [3,4],x', '$expr instanceof stdClass ? : [3,4]', 'x');
testWord('foo ::bar', 'foo ::bar');
testWord('func (1, 2)', 'func', '(1, 2)');
testWord('func(1, 2)', 'func(1, 2)');
testWord('$exp and 10', '$exp', 'and 10');
@testWord('$exp--', '$exp--'); // @ missing quotes
testWord('$exp --$a', '$exp', '--$a');
testWord('word - 1', 'word', '- 1');
testWord('word -1', 'word', '-1');
testWord('word -$num', 'word', '-$num');

Assert::error(function () {
	testWord('a-$x', 'a-$x', '');
}, E_USER_DEPRECATED, "The expression 'a-\$x' should be put in double quotes.");

Assert::error(function () {
	testWord('a-{$x}', 'a-{$x}', '');
}, E_USER_DEPRECATED, "The expression 'a-{\$x}' should be put in double quotes.");
