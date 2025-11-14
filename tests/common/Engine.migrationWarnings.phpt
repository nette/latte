<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory('temp'); // is required
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setMigrationWarnings();

Assert::error(
	fn() => $latte->renderToString('<input title="{=null}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'title\' with value null: previously it rendered as title="", now the attribute is omitted (on line 1 at column 17)',
);

Assert::error(
	fn() => $latte->renderToString('<input n:attr="aria-foo => true">'),
	E_USER_WARNING,
	'Behavior change for attribute \'aria-foo\' with value true: previously it rendered as aria-foo="1", now it renders as aria-foo="true" (on line 1 at column 8)',
);
