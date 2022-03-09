<?php

/**
 * Test: Latte\PhpWriter::formatArray()
 */

declare(strict_types=1);

use Latte\Compiler\MacroTokens;
use Latte\Compiler\PhpWriter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function formatArray($args)
{
	$writer = new PhpWriter(new MacroTokens($args));
	return $writer->formatArray();
}


test('symbols', function () {
	Assert::same('[]', formatArray(''));
	Assert::same('[1]', formatArray('1'));
	Assert::same("['symbol']", formatArray('symbol'));
	Assert::same("[1, 2, 'symbol1', 'symbol-2']", formatArray('1, 2, symbol1, symbol-2'));
});


test('expand', function () {
	Assert::same('array_merge([\'item\', $list, ], $list, [])', formatArray('item, $list, (expand) $list'));
});


test('named arguments', function () {
	Assert::same("['a' => 1]", formatArray('a: 1'));
	Assert::same("['a' => 1, 'b' => 2, 'c' =>3, 'd' =>'hello']", formatArray('a: 1, b: 2, c :3, d:hello'));
	Assert::same("['a' => ['b' => 1]]", formatArray('a: [b: 1]')); // short array syntax
	Assert::same("['a' ? \$x->b : 123]", formatArray('a ? $x->b : 123'));
});
