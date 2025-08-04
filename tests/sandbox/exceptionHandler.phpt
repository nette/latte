<?php

declare(strict_types=1);

use Latte\SourceReference;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setExceptionHandler(function () use (&$args) {
	$args = func_get_args();
});


// sandbox compile-time
$args = null;
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['=']));
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => 'before {sandbox inc.latte} after',
	'inc.latte' => '{if}',
]));

Assert::match(
	'before  after',
	$latte->renderToString('main'),
);
Assert::type(Latte\SecurityViolationException::class, $args[0]);
Assert::equal(new SourceReference(
	name: 'inc.latte',
	line: 1,
	column: 1,
	code: '{if}',
), $args[0]->getSource());
Assert::type(Latte\Runtime\Template::class, $args[1]);


// sandbox run-time
$args = null;
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['=']));
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => 'before {sandbox inc.latte} after',
	'inc.latte' => '{="trim"()}',
]));

Assert::match(
	'before  after',
	$latte->renderToString('main'),
);
Assert::type(Latte\SecurityViolationException::class, $args[0]);
Assert::null($args[0]->getSource());
Assert::type(Latte\Runtime\Template::class, $args[1]);


$latte->setExceptionHandler(fn(Throwable $e) => throw $e);
Assert::exception(
	fn() => $latte->renderToString('main'),
	Latte\SecurityViolationException::class,
);
