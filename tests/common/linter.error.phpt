<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->enablePhpLinter(PHP_BINARY);

Assert::exception(
	fn() => $latte->compile('{= [&$x] = []}'),
	Latte\CompileException::class,
	'Error in generated code: Cannot assign %a% (on line %d%)',
);
