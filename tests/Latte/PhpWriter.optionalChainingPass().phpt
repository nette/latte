<?php

/** @phpVersion < 8 */

declare(strict_types=1);

use Latte\MacroTokens;
use Latte\PhpWriter;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function optionalChaining($code)
{
	$writer = new PhpWriter(new MacroTokens);
	return $writer->optionalChainingPass(new MacroTokens($code))->joinUntil();
}


test('properties', function () {
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop)', optionalChaining('$foo?->prop'));
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop) + 10', optionalChaining('$foo?->prop + 10'));
	Assert::same('((($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop))', optionalChaining('($foo?->prop)'));
	Assert::same('[(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop)]', optionalChaining('[$foo?->prop]'));

	// variable
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->$prop)', optionalChaining('$foo?->$prop'));
});


test('calling', function () {
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->call())', optionalChaining('$foo?->call()'));
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->call()) + 10', optionalChaining('$foo?->call() + 10'));
	Assert::same('((($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->call()))', optionalChaining('($foo?->call())'));
	Assert::same('(($ʟ_tmp = $foo) === null ? null : (($ʟ_tmp = $ʟ_tmp->call( (($ʟ_tmp = $a) === null ? null : $ʟ_tmp->call()) )) === null ? null : $ʟ_tmp->x))', optionalChaining('$foo?->call( $a?->call() )?->x'));
});


test('mixed', function () {
	Assert::same('$var->prop->elem[1]->call(2)->item', optionalChaining('$var->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var) === null ? null : $ʟ_tmp->prop->elem[1]->call(2)->item)', optionalChaining('$var?->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop) === null ? null : $ʟ_tmp->elem[1]->call(2)->item)', optionalChaining('$var->prop?->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1]) === null ? null : $ʟ_tmp->call(2)->item)', optionalChaining('$var->prop->elem[1]?->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1]->call(2)) === null ? null : $ʟ_tmp->item)', optionalChaining('$var->prop->elem[1]->call(2)?->item'));
});
