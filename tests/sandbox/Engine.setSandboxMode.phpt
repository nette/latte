<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['=']));

Assert::noError(fn() => $latte->compile('{var $abc}'));

Assert::noError(fn() => $latte->renderToString('{="trim"("hello")}'));


$latte->setSandboxMode();

Assert::exception(
	fn() => $latte->compile('{var $abc}'),
	Latte\SecurityViolationException::class,
	'Tag {var} is not allowed (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->renderToString('{="trim"("hello")}'),
	Latte\SecurityViolationException::class,
	'Calling trim() is not allowed.',
);

$latte->setPolicy(null);
Assert::exception(
	fn() => $latte->compile(''),
	LogicException::class,
	'In sandboxed mode you need to set a security policy.',
);
