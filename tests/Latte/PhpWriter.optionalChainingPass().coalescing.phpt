<?php

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
	Assert::same('(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->prop)', optionalChaining('$foo??->prop'));
	Assert::same('((($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->prop))', optionalChaining('($foo??->prop)'));
	Assert::same('[(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->prop)]', @optionalChaining('[$foo??->prop]'));

	// variable
	Assert::same('(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->$prop)', optionalChaining('$foo??->$prop'));
});


test('calling', function () {
	Assert::same('(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->call())', optionalChaining('$foo??->call()'));
	Assert::same('((($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->call()))', optionalChaining('($foo??->call())'));
	Assert::same('(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp->call( (($ʟ_tmp = $a ?? null) === null ? null : $ʟ_tmp->call()) ))', @optionalChaining('$foo??->call( $a??->call() )'));
});


test('mixed', function () {
	Assert::same('$var->prop->elem[1]->call(2)->item', optionalChaining('$var->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var ?? null) === null ? null : $ʟ_tmp->prop->elem[1]->call(2)->item)', optionalChaining('$var??->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop ?? null) === null ? null : $ʟ_tmp->elem[1]->call(2)->item)', optionalChaining('$var->prop??->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1] ?? null) === null ? null : $ʟ_tmp->call(2)->item)', optionalChaining('$var->prop->elem[1]??->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1]->call(2) ?? null) === null ? null : $ʟ_tmp->item)', optionalChaining('$var->prop->elem[1]->call(2)??->item'));
});


test('not allowed', function () {
	Assert::same('$foo??', optionalChaining('$foo??'));
});
