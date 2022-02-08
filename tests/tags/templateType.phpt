<?php

/**
 * Test: {templateType}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{templateType}');
}, Latte\CompileException::class, 'Missing class name in {templateType}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if true}{templateType stdClass}{/if}');
}, Latte\CompileException::class, '{templateType} is allowed only in template header.');

Assert::noError(function () use ($latte) {
	$latte->compile('{templateType stdClass}');
});
