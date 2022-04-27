<?php

/**
 * Test: {try} ... {else} {rollback} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{rollback}'),
	Latte\CompileException::class,
	'Tag {rollback} must be inside {try} ... {/try} (at column 1)',
);
