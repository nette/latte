<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->setCacheDirectory(getTempDir()); // is required to output position
$latte->setFeature(Latte\Feature::MigrationWarnings);

Assert::error(
	fn() => $latte->renderToString('<input title="{=null}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'title\' with value null: previously it rendered as title="", now the attribute is omitted (on line 1 at column 17)',
);

Assert::error(
	fn() => $latte->renderToString('<a href="{=null}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'href\' with value null: previously it rendered as href="", now the attribute is omitted (on line 1 at column 12)',
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

Assert::error(
	fn() => $latte->renderToString('<div contenteditable="{=false}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'contenteditable\' with value false: previously it rendered as contenteditable="", now it renders as contenteditable="false" (on line 1 at column 25)',
);

Assert::error(
	fn() => $latte->renderToString('<iframe srcdoc="{=\'<b>x</b>\'}">'),
	E_USER_WARNING,
	'Behavior change for attribute \'srcdoc\' with string value: previously it was rendered as HTML, now it is escaped as text (wrap trusted HTML in Latte\Runtime\Html) (on line 1 at column 19)',
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

// toggle overrides the category derived from the attribute name
Assert::same(
	'<div draggable>',
	$latte->renderToString('<div contenteditable="{=false|toggle}" draggable="{=true|toggle}" hidden="{=false|toggle}">'),
);
