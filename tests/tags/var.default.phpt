<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function ws(string $s): string
{
	return preg_replace('~\s+~', ' ', $s);
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

test('{var ...}', function () use ($latte) {
	Assert::contains('$var = null; $var2 = null /*', ws($latte->compile('{var $var, $var2,}')));
	Assert::contains('$var = 123 /*', $latte->compile('{var $var = 123}'));
	Assert::contains('$var1 = 123; $var2 = \'nette framework\' /*', ws($latte->compile('{var $var1 = 123, $var2 = "nette framework"}')));
	Assert::contains('$temp->var1 = 123 /*', $latte->compile('{var $temp->var1 = 123}'));

	// types
	Assert::contains('$temp->var1 = 123 /*', $latte->compile('{var int $temp->var1 = 123}'));
	Assert::contains('$temp->var1 = 123 /*', $latte->compile('{var null|int|string[] $temp->var1 = 123}'));
	Assert::contains('$var1 = 123; $var2 = \'nette framework\' /*', ws($latte->compile('{var int|string[] $var1 = 123, ?class $var2 = "nette framework"}')));
	Assert::contains('$var1 = 123; $var2 = 456 /*', ws($latte->compile('{var A\B $var1 = 123, ?A\B $var2 = 456}')));
	Assert::contains('$var1 = 123; $var2 = 456 /*', ws($latte->compile('{var \A\B $var1 = 123, ?\A\B $var2 = 456}')));

	// errors
	Assert::exception(
		fn() => $latte->compile('{var int var, string var2}'),
		Latte\CompileException::class,
		'Unexpected end %a%',
	);

	// preprocess
	Assert::contains("\$temp->var1 = true ? 'a' : null /*", $latte->compile('{var $temp->var1 = true ? a}'));
});


test('{default ...}', function () use ($latte) {
	Assert::contains("extract(['var' => null, 'var2' => null], EXTR_SKIP) /*", $latte->compile('{default $var, $var2}'));
	Assert::contains("extract(['var' => 123], EXTR_SKIP) /*", $latte->compile('{default $var = 123}'));
	Assert::contains("extract(['var1' => 123, 'var2' => 'nette framework'], EXTR_SKIP) /*", $latte->compile('{default $var1 = 123, $var2 = "nette framework"}'));

	// types
	Assert::contains("extract(['var' => 123], EXTR_SKIP) /*", $latte->compile('{default null|int|string[] $var = 123}'));
	Assert::contains("extract(['var1' => 123, 'var2' => 'nette framework'], EXTR_SKIP) /*", $latte->compile('{default int|string[] $var1 = 123, ?class $var2 = "nette framework"}'));

	// errors
	Assert::exception(
		fn() => $latte->compile('{default $temp->var1 = 123}'),
		Latte\CompileException::class,
		"Unexpected '\$temp->' in {default} (on line 1 at column 10)",
	);

	Assert::exception(
		fn() => $latte->compile('{default int var, string var2}'),
		Latte\CompileException::class,
		'Unexpected end in {default} (on line 1 at column 30)',
	);

	// preprocess
	Assert::contains("extract(['var1' => true ? 'a' : null], EXTR_SKIP) /*", $latte->compile('{default $var1 = true ? a}'));
});
