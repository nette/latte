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


// assignments
Assert::exception(
	fn() => parseCode('trim() = $x'),
	Latte\CompileException::class,
	'Cannot write to the expression: trim() (on line 1 at column 1)',
);

Assert::exception(
	fn() => parseCode('$x = & $x?->y'),
	Latte\CompileException::class,
	'Cannot take reference to the expression: $x?->y (on line 1 at column 8)',
);


// isset
Assert::exception(
	fn() => parseCode('isset(1 + 1)'),
	Latte\CompileException::class,
	'Cannot use isset() on expression: 1 + 1 (on line 1 at column 7)',
);


// array vs list
Assert::exception(
	fn() => parseCode('[1, , 2]'),
	Latte\CompileException::class,
	'Cannot use empty array elements or list() in arrays (on line 1 at column 3)',
);

Assert::exception(
	fn() => parseCode('[...$k] = $x'),
	Latte\CompileException::class,
	'Spread operator is not supported in assignments (on line 1 at column 5)',
);

Assert::exception(
	fn() => parseCode('[list($x)]'),
	Latte\CompileException::class,
	'Cannot use empty array elements or list() in arrays (on line 1 at column 2)',
);

Assert::exception(
	fn() => parseCode('[array($x)] = []'),
	Latte\CompileException::class,
	'Cannot write to the expression: [$x] (on line 1 at column 2)',
);

Assert::exception(
	fn() => parseCode('list(1 + 1) = $x'),
	Latte\CompileException::class,
	'Cannot write to the expression: 1 + 1 (on line 1 at column 6)',
);
