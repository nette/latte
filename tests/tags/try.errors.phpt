<?php

/**
 * Test: {try} ... {else} {rollback} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::exception(
	fn() => $latte->compile('{rollback}'),
	Latte\CompileException::class,
	'Tag {rollback} must be inside {try} ... {/try} (on line 1 at column 1)',
);
