<?php

/**
 * Test: Latte\Engine and blocks.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match('', $latte->renderToString('{define foobar}Hello{/define}'));

Assert::match('', $latte->renderToString('{define foo-bar}Hello{/define}'));

Assert::match('', $latte->renderToString('{define $foo}Hello{/define}', ['foo' => 'bar']));

// no empty line
Assert::match(
	<<<'XX'
		one
		two
		XX,
	$latte->renderToString(
		<<<'XX'
			one
			{define foo}Hello{/define}
			two
			XX,
	),
);

Assert::exception(function () use ($latte) {
	$latte->renderToString('{define _foobar}Hello{/define}');
}, Latte\CompileException::class, "Block name must start with letter a-z, '_foobar' given.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{define 123}Hello{/define}');
}, Latte\CompileException::class, "Block name must start with letter a-z, '123' given.");
