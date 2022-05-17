<?php

/**
 * Test: {templateType}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{templateType}'),
	Latte\CompileException::class,
	'Missing class name in {templateType} (at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{if true}{templateType stdClass}{/if}'),
	Latte\CompileException::class,
	'{templateType} is allowed only in template header (at column 10)',
);

Assert::noError(fn() => $latte->compile('{templateType stdClass}'));
