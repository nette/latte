<?php

/**
 * Test: {do}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%$a = \'test\' ? [] : null%A%',
	$latte->compile('{do $a = test ? ([])}'),
);


// reserved keywords
Assert::exception(
	fn() => $latte->compile('{do break}'),
	Latte\CompileException::class,
	"Keyword 'break' cannot be used in Latte (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{do exit}'),
	Latte\CompileException::class,
	"Keyword 'exit' cannot be used in Latte (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{do return}'),
	Latte\CompileException::class,
	"Keyword 'return' cannot be used in Latte (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{php function test() }'),
	Latte\CompileException::class,
	"Keyword 'function' cannot be used in Latte (on line 1 at column 6)",
);
