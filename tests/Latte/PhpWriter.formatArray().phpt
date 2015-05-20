<?php

/**
 * Test: Latte\PhpWriter::formatArray()
 */

use Latte\PhpWriter,
	Latte\MacroTokens,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function formatArray($args) {
	$writer = new PhpWriter(new MacroTokens($args));
	return $writer->formatArray();
}


test(function() { // symbols
	Assert::same( '[]',  formatArray('') );
	Assert::same( '[1]',  formatArray('1') );
	Assert::same( "['symbol']",  formatArray('symbol') );
	Assert::same( "[1, 2, 'symbol1', 'symbol-2']",  formatArray('1, 2, symbol1, symbol-2') );
});


test(function() { // expand
	Assert::same( 'array_merge([\'item\', $list, ], $list, [])',  formatArray('item, $list, (expand) $list') );
});
