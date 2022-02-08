<?php

declare(strict_types=1);

use Latte\MacroTokens;
use Latte\PhpWriter;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function argumentsPass($code)
{
	$writer = new PhpWriter(new MacroTokens);
	return $writer->namedArgumentsPass(new MacroTokens($code))->joinUntil();
}


test('ok', function () {
	Assert::same("'a' => 1", argumentsPass('a: 1'));
	Assert::same("'a' => 1, 'b' => 2, 'c' =>3, 'd' =>hello", argumentsPass('a: 1, b: 2, c :3, d:hello'));
});

test('nested', function () {
	Assert::same("'a' => [b: 1]", argumentsPass('a: [b: 1]'));
});

test('ternary', function () {
	Assert::same('a ? $x->b : 123', argumentsPass('a ? $x->b : 123'));
});
