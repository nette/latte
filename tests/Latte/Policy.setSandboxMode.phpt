<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowMacros(['=']));

Assert::noError(function () use ($latte) {
	$latte->compile('{var $abc}');
	$latte->renderToString('{="trim"("hello")}');
});


$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{var $abc}');
}, Latte\CompileException::class, 'Tag {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{="trim"("hello")}');
}, Latte\SecurityViolationException::class, 'Calling trim() is not allowed.');

$latte->setPolicy(null);
Assert::exception(function () use ($latte) {
	$latte->compile('');
}, LogicException::class, 'In sandboxed mode you need to set a security policy.');
