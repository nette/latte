<?php

/**
 * Test: unexpected macro.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('Block{/block}');
}, 'Latte\CompileException', 'Unexpected {/block}');
