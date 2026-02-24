<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

// default
$latte->setSyntax('single');
Assert::same(
	' single {double}',
	$latte->renderToString('{* comment *} {=single} {{=double}}'),
);


$latte->setSyntax('double');
Assert::same(
	'{* comment *} {=single} double',
	$latte->renderToString('{* comment *} {=single} {{=double}}'),
);


// overridden
$latte->setSyntax('double');
Assert::same(
	'  single {double}',
	$latte->renderToString('{{syntax single}} {* comment *} {=single} {{=double}}'),
);


$latte->setSyntax('off');
Assert::same(
	'{* comment *} {=single} {{=double}}',
	$latte->renderToString('{* comment *} {=single} {{=double}}'),
);


$latte->setSyntax('unknown');
Assert::exception(
	fn() => $latte->renderToString('{* comment *} {=single} {{=double}}'),
	Latte\CompileException::class,
	"Thrown exception 'Unknown syntax 'unknown''",
);
