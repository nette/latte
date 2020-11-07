<?php

/**
 * Test: {varType}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{varType}');
}, Latte\CompileException::class, 'Missing arguments in {varType}');

Assert::exception(function () use ($latte) {
	$latte->compile('{varType type}');
}, Latte\CompileException::class, 'Unexpected content%a%');

Assert::exception(function () use ($latte) {
	$latte->compile('{varType type var}');
}, Latte\CompileException::class, 'Unexpected content%a%');

Assert::exception(function () use ($latte) {
	$latte->compile('{varType $var type}');
}, Latte\CompileException::class, 'Unexpected content%a%');

Assert::noError(function () use ($latte) {
	$latte->compile('{varType type $var}');
});

Assert::noError(function () use ($latte) {
	$latte->compile('{varType ?\Nm\Class $var}');
});

Assert::noError(function () use ($latte) {
	$latte->compile('{varType int|null $var}');
});

Assert::noError(function () use ($latte) {
	$latte->compile('{varType array{0: int, 1: int} $var}');
});
