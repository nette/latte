<?php

/** @phpVersion 8 */

declare(strict_types=1);

use Latte\Compiler\MacroTokens;
use Latte\Compiler\PhpWriter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function optionalChaining($code)
{
	$writer = new PhpWriter(new MacroTokens);
	return $writer->optionalChainingPass(new MacroTokens($code))->joinUntil();
}


test('mixed', function () {
	Assert::same('$var->prop->elem[1]->call(2)->item', optionalChaining('$var->prop->elem[1]->call(2)->item'));
	Assert::same('$var?->prop->elem[1]->call(2)->item', optionalChaining('$var?->prop->elem[1]->call(2)->item'));
	Assert::same('$var->prop?->elem[1]->call(2)->item', optionalChaining('$var->prop?->elem[1]->call(2)->item'));
	Assert::same('$var->prop->elem[1]?->call(2)->item', optionalChaining('$var->prop->elem[1]?->call(2)->item'));
	Assert::same('$var->prop->elem[1]->call(2)?->item', optionalChaining('$var->prop->elem[1]->call(2)?->item'));
});
