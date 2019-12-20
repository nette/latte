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


test(function () {
	Assert::same('$a', optionalChaining('$a'));
	Assert::same('($a ?? null)', optionalChaining('$a?'));
	Assert::same('(($a ?? null))', optionalChaining('($a?)'));
	Assert::same('a?', optionalChaining('a?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : $_tmp[1])', optionalChaining('$foo?[1]'));
	Assert::same('($foo[1] ?? null)', optionalChaining('$foo[1]?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp[1] ?? null))', optionalChaining('$foo?[1]?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp[1] ?? null)) + 10', optionalChaining('$foo?[1]? + 10'));
	Assert::same('(($foo[1] ?? null))', optionalChaining('($foo[1]?)'));
	Assert::same('((($_tmp = $foo ?? null) === null ? null : $_tmp[1]))', optionalChaining('($foo?[1])'));
	Assert::same('[(($_tmp = $foo ?? null) === null ? null : ($_tmp[1] ?? null))]', optionalChaining('[$foo?[1]?]'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp[ ($a ?? null) ] ?? null))', optionalChaining('$foo?[ $a? ]?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp[ (($_tmp = $a ?? null) === null ? null : ($_tmp[2] ?? null)) ] ?? null))', optionalChaining('$foo?[ $a?[2]? ]?'));

	Assert::same('(($_tmp = $foo ?? null) === null ? null : $_tmp->prop)', optionalChaining('$foo?->prop'));
	Assert::same('($foo->prop ?? null)', optionalChaining('$foo->prop?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->prop ?? null))', optionalChaining('$foo?->prop?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->prop ?? null)) + 10', optionalChaining('$foo?->prop? + 10'));
	Assert::same('($foo->prop ?? null) + 10', optionalChaining('$foo->prop? + 10'));
	Assert::same('(($foo->prop ?? null))', optionalChaining('($foo->prop?)'));
	Assert::same('((($_tmp = $foo ?? null) === null ? null : $_tmp->prop))', optionalChaining('($foo?->prop)'));
	Assert::same('[(($_tmp = $foo ?? null) === null ? null : ($_tmp->prop ?? null))]', optionalChaining('[$foo?->prop?]'));

	Assert::same('(($_tmp = $foo ?? null) === null ? null : $_tmp->call())', optionalChaining('$foo?->call()'));
	Assert::same('($foo->call() ?? null)', optionalChaining('$foo->call()?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->call() ?? null))', optionalChaining('$foo?->call()?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->call() ?? null)) + 10', optionalChaining('$foo?->call()? + 10'));
	Assert::same('($foo->call() ?? null) + 10', optionalChaining('$foo->call()? + 10'));
	Assert::same('(($foo->call() ?? null))', optionalChaining('($foo->call()?)'));
	Assert::same('((($_tmp = $foo ?? null) === null ? null : $_tmp->call()))', optionalChaining('($foo?->call())'));
	Assert::same('((($_tmp = $foo ?? null) === null ? null : ($_tmp->call() ?? null)))', optionalChaining('($foo?->call()?)'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->call( ($a ?? null) ) ?? null))', optionalChaining('$foo?->call( $a? )?'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : ($_tmp->call( (($_tmp = $a ?? null) === null ? null : $_tmp->call()) ) ?? null))', optionalChaining('$foo?->call( $a?->call() )?'));

	Assert::same('($foo->prop ?? null) + (($_tmp = $foo ?? null) === null ? null : ($_tmp->prop ?? null))', optionalChaining('$foo->prop? + $foo?->prop?'));

	Assert::same('$var->prop->elem[1]->call(2)->item', optionalChaining('$var->prop->elem[1]->call(2)->item'));
	Assert::same('(($_tmp = $var ?? null) === null ? null : $_tmp->prop->elem[1]->call(2)->item)', optionalChaining('$var?->prop->elem[1]->call(2)->item'));
	Assert::same('(($_tmp = $var->prop ?? null) === null ? null : $_tmp->elem[1]->call(2)->item)', optionalChaining('$var->prop?->elem[1]->call(2)->item'));
	Assert::same('(($_tmp = $var->prop->elem ?? null) === null ? null : $_tmp[1]->call(2)->item)', optionalChaining('$var->prop->elem?[1]->call(2)->item'));
	Assert::same('(($_tmp = $var->prop->elem[1] ?? null) === null ? null : $_tmp->call(2)->item)', optionalChaining('$var->prop->elem[1]?->call(2)->item'));
	Assert::same('(($_tmp = $var->prop->elem[1]->call(2) ?? null) === null ? null : $_tmp->item)', optionalChaining('$var->prop->elem[1]->call(2)?->item'));
	Assert::same('($var->prop->elem[1]->call(2)->item ?? null)', optionalChaining('$var->prop->elem[1]->call(2)->item?'));
	Assert::same(
		'(($_tmp = $var ?? null) === null ? null : (($_tmp = $_tmp->prop ?? null) === null ? null : (($_tmp = $_tmp->elem ?? null) === null ? null : (($_tmp = $_tmp[1] ?? null) === null ? null : (($_tmp = $_tmp->call(2) ?? null) === null ? null : ($_tmp->item ?? null))))))',
		optionalChaining('$var?->prop?->elem?[1]?->call(2)?->item?')
	);
});


test(function () { // not allowed
	Assert::same('$foo ?(hello)', optionalChaining('$foo?(hello)'));
	Assert::same('$foo->foo ?(hello)', optionalChaining('$foo->foo?(hello)'));
});


test(function () { // ternary
	Assert::same('$a ?:$b', optionalChaining('$a?:$b'));
	Assert::same('$a ? : $b', optionalChaining('$a ? : $b'));
	Assert::same('$a ?? $b', optionalChaining('$a ?? $b'));
	Assert::same('$a ? $a->a() : $a', optionalChaining('$a ? $a->a() : $a'));

	Assert::same('$a ? [1, 2, ([3 ? 2 : 1])]: $b', optionalChaining('$a ? [1, 2, ([3 ? 2 : 1])]: $b'));
	Assert::same('$a->foo ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a->foo ? [1, 2, ([3 ? 2 : 1])] : $b'));
	Assert::same('(($_tmp = $a ?? null) === null ? null : $_tmp->foo) ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a?->foo ? [1, 2, ([3 ? 2 : 1])] : $b'));
	Assert::same('(($_tmp = $a ?? null) === null ? null : ($_tmp->foo ?? null)) ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a?->foo? ? [1, 2, ([3 ? 2 : 1])] : $b'));
	Assert::same('($a->foo ?? null) ? [1, 2, ([3 ? 2 : 1])] : $b', optionalChaining('$a->foo? ? [1, 2, ([3 ? 2 : 1])] : $b'));

	Assert::same('$a ? \Foo::BAR : \Foo::BAR', optionalChaining('$a ? \Foo::BAR : \Foo::BAR'));
});
