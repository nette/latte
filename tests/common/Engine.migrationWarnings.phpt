<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->setTempDirectory(getTempDir()); // is required to output position
$latte->setMigrationWarnings();

Assert::error(
	fn() => $latte->renderToString('<input title="{=null}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'title\' with value null: previously it rendered as title="", now the attribute is omitted (on line 1 at column 17)',
);

Assert::error(
	fn() => $latte->renderToString('<input onclick="{=foo}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'onclick\' with string value: previously it was JSON-encoded, now it is rendered as a string (on line 1 at column 19)',
);

Assert::error(
	fn() => $latte->renderToString('<input data-foo="{=true}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'data-foo\' with value true: previously it rendered as data-foo="1", now it renders as data-foo="true" (on line 1 at column 20)',
);

Assert::error(
	fn() => $latte->renderToString('<input n:attr="aria-foo => true">'),
	E_USER_WARNING,
	'Behavior change for attribute \'aria-foo\' with value true: previously it rendered as aria-foo="1", now it renders as aria-foo="true" (on line 1 at column 8)',
);

// |accept
Assert::same(
	'<input>',
	$latte->renderToString('<input title="{=null|accept}">'),
);

Assert::error(
	fn() => $latte->renderToString('<input title="{=true|accept}">'),
	E_USER_WARNING,
	'Invalid value for attribute \'title\': bool is not allowed (on line 1 at column 17)',
);

// toggle
Assert::same(
	'<input true>',
	$latte->renderToString('<input true="{=true|toggle}" false="{=false|toggle}">'),
);
