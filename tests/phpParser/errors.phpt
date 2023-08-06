<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// TagParser::parseArguments() must not contain list(...)
Assert::exception(
	fn() => parseCode('list($x)'),
	Latte\CompileException::class,
	'Unexpected end (on line 1 at column 9)',
);
