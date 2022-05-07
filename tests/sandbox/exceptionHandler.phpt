<?php

declare(strict_types=1);

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
	$latte->renderToString('main')
);
Assert::type(Latte\CompileException::class, $args[0]);
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
	$latte->renderToString('main')
);
Assert::type(Latte\SecurityViolationException::class, $args[0]);
Assert::type(Latte\Runtime\Template::class, $args[1]);
