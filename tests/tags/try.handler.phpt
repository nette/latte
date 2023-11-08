<?php

/**
 * Test: {try} ... {else} {rollback} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class CustomException extends Exception
{
}


function error()
{
	throw new CustomException;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setExceptionHandler(function () use (&$args) {
	$args = func_get_args();
});

$args = null;
$latte->renderToString('{try}{=error()}{/try}');
Assert::type(CustomException::class, $args[0]);
Assert::type(Latte\Runtime\Template::class, $args[1]);


$args = null;
$latte->renderToString('{try}{=error()}{else}{/try}');
Assert::type(CustomException::class, $args[0]);
Assert::type(Latte\Runtime\Template::class, $args[1]);


Assert::exception(
	fn() => $latte->renderToString('{try}{=error()}{else}{=error()}{/try}'),
	CustomException::class,
);


$args = null;
$latte->renderToString('{try}{rollback}{/try}');
Assert::null($args);


$latte->setExceptionHandler(fn(Throwable $e) => throw $e);

Assert::exception(
	fn() => $latte->renderToString('{try}{=error()}{/try}'),
	CustomException::class,
);
