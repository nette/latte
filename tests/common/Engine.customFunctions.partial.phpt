<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$latte = createLatte();
$latte->addFunction('myFunc', fn($x, $y = 1) => $x * $y);
$latte->addFunction('aware', fn(Latte\Runtime\Template $template, $x, $y) => $x . $y);

if (PHP_VERSION_ID < 80600) {
	Assert::exception(
		fn() => $latte->compile('{=myFunc(...)}'),
		Latte\CompileException::class,
		"Partial application of custom function 'myFunc' requires PHP 8.6%A%",
	);
	exit;
}

Assert::same('6', $latte->renderToString('{var $fn = myFunc(...)}{=$fn(2, 3)}'));
Assert::same('6', $latte->renderToString('{var $fn = myFunc(?, 3)}{=$fn(2)}'));
Assert::same('6', $latte->renderToString('{var $fn = myFunc(2, ...)}{=$fn(3)}'));
Assert::same('ab', $latte->renderToString('{var $fn = aware(?, y: "b")}{=$fn("a")}'));
Assert::same('ab', $latte->renderToString('{var $fn = aware(y: ?, x: "a")}{=$fn("b")}'));


// sandbox
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['var', '='])->allowFunctions(['myFunc']));
$latte->setSandboxMode();

Assert::same('6', $latte->renderToString('{var $fn = myFunc(?, 3)}{=$fn(2)}'));
Assert::same('6', $latte->renderToString('{var $fn = myFunc(...)}{=$fn(2, 3)}'));

Assert::exception(
	fn() => $latte->compile('{var $fn = aware(?, "b")}'),
	Latte\SecurityViolationException::class,
	'Function aware() is not allowed%A%',
);
