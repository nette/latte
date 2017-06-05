<?php

/**
 * Test: Deprecated Latte\Engine parts
 *
 * @author     Miloslav Hůla
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$latte = new Latte\Engine;

	Assert::error(function() use ($latte) {
		$latte->compiler;
		$latte->parser;
	}, array(
		array(E_USER_DEPRECATED, 'Magic getters are deprecated. Use getCompiler() method insted.'),
		array(E_USER_DEPRECATED, 'Magic getters are deprecated. Use getParser() method insted.'),
	));

	Assert::same($latte->getCompiler(), @$latte->compiler);
	Assert::same($latte->getParser(), @$latte->parser);
});
