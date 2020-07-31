<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// global function
$latte->addFunction('fnc', 'trim');
Assert::same('aa', $latte->invokeFunction('fnc', ['  aa  ']));
Assert::same(
	'abc',
	$latte->renderToString('{fnc(" abc ")}')
);


// static method
$latte->addFunction('fnc', 'Latte\Runtime\Filters::reverse');
Assert::same(
	' cba ',
	$latte->renderToString('{fnc(" abc ")}')
);


// static method 2
$latte->addFunction('fnc', [Latte\Runtime\Filters::class, 'reverse']);
Assert::same(
	' cba ',
	$latte->renderToString('{fnc(" abc ")}')
);


// object method
class Test
{
	public function m($val)
	{
		return $val * 2;
	}
}

$latte->addFunction('fnc', [new Test, 'm']);
Assert::same(
	'246',
	$latte->renderToString('{fnc(123)}')
);


// closure
$latte->addFunction('fnc', function ($val) {
	return $val * 2;
});
Assert::same(
	'246',
	$latte->renderToString('{fnc(123)}')
);


// case insensitive
$latte->addFunction('CaSe', 'trim');
Assert::same(
	'abc',
	$latte->renderToString('{CaSe(" abc ")}')
);

Assert::error(function () use ($latte) {
	$latte->compile('{casE(123)}');
}, E_USER_WARNING, "Case mismatch on function name 'casE', correct name is 'CaSe'.");


// with inline modifier
$latte->addFunction('fnc', function ($val) {
	return strrev($val);
});
Assert::same(
	'CBA',
	$latte->renderToString('{fnc(abc|upper)}')
);


// invoke function
Assert::exception(function () use ($latte) {
	$latte->invokeFunction('unknown', []);
}, LogicException::class, "Function 'unknown' is not defined.");
