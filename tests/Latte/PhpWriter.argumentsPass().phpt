<?php

declare(strict_types=1);

use Latte\MacroTokens;
use Latte\PhpWriter;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function argumentsPass($code)
{
	$writer = new PhpWriter(new MacroTokens);
	return $writer->argumentsPass(new MacroTokens($code))->joinUntil();
}


test('', function () { // vars
	Assert::same('$a', argumentsPass('$a'));
	Assert::same("'a'=> 1", argumentsPass('$a => 1'));
});
