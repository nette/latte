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


test('vars', function () { // deprecated
	Assert::same('$a', optionalChaining('$a'));
	Assert::same('($a ?? null)', @optionalChaining('$a?'));
	Assert::same('(($a ?? null))', @optionalChaining('($a?)'));
	Assert::same('a?', optionalChaining('a?'));
});


test('indexes', function () { // deprecated
	Assert::same('($foo[1] ?? null)', @optionalChaining('$foo[1]?'));
	Assert::same('(($foo[1] ?? null))', @optionalChaining('($foo[1]?)'));
});


test('properties', function () {
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop)', optionalChaining('$foo?->prop'));
	Assert::same('($foo->prop ?? null)', @optionalChaining('$foo->prop?')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->prop ?? null))', @optionalChaining('$foo?->prop?')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->prop ?? null)) + 10', @optionalChaining('$foo?->prop? + 10')); // deprecated
	Assert::same('($foo->prop ?? null) + 10', @optionalChaining('$foo->prop? + 10')); // deprecated
	Assert::same('(($foo->prop ?? null))', @optionalChaining('($foo->prop?)')); // deprecated
	Assert::same('((($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->prop))', optionalChaining('($foo?->prop)'));
	Assert::same('[(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->prop ?? null))]', @optionalChaining('[$foo?->prop?]')); // deprecated

	// variable
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->$prop)', optionalChaining('$foo?->$prop'));
	Assert::same('($foo->$prop ?? null)', @optionalChaining('$foo->$prop?')); // deprecated

	// static
	Assert::same('(($ʟ_tmp = $foo ?? null) === null ? null : $ʟ_tmp::$prop)', @optionalChaining('$foo?::$prop')); // deprecated
	Assert::same('($foo::$prop ?? null)', @optionalChaining('$foo::$prop?')); // deprecated
});


test('calling', function () {
	Assert::same('(($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->call())', optionalChaining('$foo?->call()'));
	Assert::same('($foo->call() ?? null)', @optionalChaining('$foo->call()?')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->call() ?? null))', @optionalChaining('$foo?->call()?')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->call() ?? null)) + 10', @optionalChaining('$foo?->call()? + 10')); // deprecated
	Assert::same('($foo->call() ?? null) + 10', @optionalChaining('$foo->call()? + 10')); // deprecated
	Assert::same('(($foo->call() ?? null))', @optionalChaining('($foo->call()?)')); // deprecated
	Assert::same('((($ʟ_tmp = $foo) === null ? null : $ʟ_tmp->call()))', optionalChaining('($foo?->call())'));
	Assert::same('((($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->call() ?? null)))', @optionalChaining('($foo?->call()?)')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->call( ($a ?? null) ) ?? null))', @optionalChaining('$foo?->call( $a? )?')); // deprecated
	Assert::same('(($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->call( (($ʟ_tmp = $a) === null ? null : $ʟ_tmp->call()) ) ?? null))', @optionalChaining('$foo?->call( $a?->call() )?')); // deprecated
});


test('mixed', function () {
	Assert::same('($foo->prop ?? null) + (($ʟ_tmp = $foo) === null ? null : ($ʟ_tmp->prop ?? null))', @optionalChaining('$foo->prop? + $foo?->prop?')); // deprecated

	Assert::same('$var->prop->elem[1]->call(2)->item', optionalChaining('$var->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var) === null ? null : $ʟ_tmp->prop->elem[1]->call(2)->item)', optionalChaining('$var?->prop->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop) === null ? null : $ʟ_tmp->elem[1]->call(2)->item)', optionalChaining('$var->prop?->elem[1]->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1]) === null ? null : $ʟ_tmp->call(2)->item)', optionalChaining('$var->prop->elem[1]?->call(2)->item'));
	Assert::same('(($ʟ_tmp = $var->prop->elem[1]->call(2)) === null ? null : $ʟ_tmp->item)', optionalChaining('$var->prop->elem[1]->call(2)?->item'));
	Assert::same('($var->prop->elem[1]->call(2)->item ?? null)', @optionalChaining('$var->prop->elem[1]->call(2)->item?')); // deprecated
});


test('not allowed', function () {
	Assert::same('$foo ?(hello)', optionalChaining('$foo?(hello)'));
	Assert::same('$foo->foo ?(hello)', optionalChaining('$foo->foo?(hello)'));

	Assert::same('$foo ?[1]', optionalChaining('$foo?[1]')); // not allowed due to collision with short ternary

	Assert::same('Class::$prop?', optionalChaining('Class::$prop?'));
	Assert::same('$$var?', optionalChaining('$$var?'));
});


test('ternary', function () {
	Assert::same('$a ?:$b', optionalChaining('$a?:$b'));
	Assert::same('$a ? : $b', optionalChaining('$a ? : $b'));
	Assert::same('$a ?? $b', optionalChaining('$a ?? $b'));
	Assert::same('$a ? $a->a() : $a', optionalChaining('$a ? $a->a() : $a'));

	Assert::same('$a ? [1, 2, ([3 ? 2 : 1])]: $b', optionalChaining('$a ? [1, 2, ([3 ? 2 : 1])]: $b'));
	Assert::same('$a->foo ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a->foo ? [1, 2, ([3 ? 2 : 1])] : $b'));
	Assert::same('(($ʟ_tmp = $a) === null ? null : $ʟ_tmp->foo) ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a?->foo ? [1, 2, ([3 ? 2 : 1])] : $b'));
	Assert::same('(($ʟ_tmp = $a) === null ? null : ($ʟ_tmp->foo ?? null)) ? [1, 2, ([3 ? 2 : 1])] : $b', @optionalChaining('$a?->foo? ? [1, 2, ([3 ? 2 : 1])] : $b')); // deprecated
	Assert::same('($a->foo ?? null) ? [1, 2, ([3 ? 2 : 1])] : $b', @optionalChaining('$a->foo? ? [1, 2, ([3 ? 2 : 1])] : $b')); // deprecated

	Assert::same('$a ? \Foo::BAR : \Foo::BAR', optionalChaining('$a ? \Foo::BAR : \Foo::BAR'));
});
