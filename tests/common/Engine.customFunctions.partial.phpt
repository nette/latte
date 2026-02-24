<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$latte = createLatte();
$latte->addFunction('myFunc', fn($x) => $x * 2);

Assert::exception(
	fn() => $latte->compile('{=myFunc(...)}'),
	Latte\CompileException::class,
	"Custom function 'myFunc' cannot be used as partial function%A%",
);
