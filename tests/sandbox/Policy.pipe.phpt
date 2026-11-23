<?php declare(strict_types=1);

/**
 * Test: the pipe operator calls through the policy
 * @phpversion 8.5
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Account
{
	public function getToken()
	{
		return 'secret';
	}
}


$latte = createLatte();
$latte->setPolicy(Latte\Sandbox\SecurityPolicy::createSafePolicy()->allowFunctions(['strtoupper', 'trim']));
$latte->setSandboxMode();


test('allowed function is called', function () use ($latte) {
	Assert::same('ABC', $latte->renderToString('{="abc" |> "strtoupper"}'));
	Assert::same('ABC', $latte->renderToString('{="abc" |> strtoupper(...)}'));
	Assert::same('ABC', $latte->renderToString('{="abc" |> "trim" |> "strtoupper"}'));
});


test('left side is evaluated first', function () use ($latte) {
	$log = [];
	$value = function () use (&$log) {
		$log[] = 'value';
		return 'abc';
	};
	$callable = function () use (&$log) {
		$log[] = 'callable';
		return 'strtoupper';
	};
	Assert::same('ABC', $latte->renderToString('{=$value() |> $callable()}', ['value' => $value, 'callable' => $callable]));
	Assert::same(['value', 'callable'], $log);
});


test('forbidden callable is refused', function () use ($latte) {
	Assert::exception(
		fn() => $latte->renderToString('{="whoami" |> "shell_exec"}'),
		Latte\SecurityViolationException::class,
		'Calling shell_exec() is not allowed.',
	);

	Assert::exception(
		fn() => $latte->renderToString('{=null |> [$account, "getToken"]}', ['account' => new Account]),
		Latte\SecurityViolationException::class,
		'Calling Account::getToken() is not allowed.',
	);

	Assert::exception(
		fn() => $latte->renderToString('{="shell_exec" |> "Closure::fromCallable"}'),
		Latte\SecurityViolationException::class,
		'Calling Closure::fromCallable() is not allowed.',
	);

	Assert::exception(
		fn() => $latte->compile('{="abc" |> getenv(...)}'),
		Latte\SecurityViolationException::class,
		'Function getenv() is not allowed (on line 1 at column 12)',
	);
});


test('{sandbox} calls through the policy', function () {
	$latte = createLatte();
	$latte->setPolicy(Latte\Sandbox\SecurityPolicy::createSafePolicy()->allowFunctions(['strtoupper']));
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'allowed' => '{=" abc" |> "trim"} {sandbox allowed.latte}',
		'forbidden' => '{sandbox forbidden.latte}',
		'allowed.latte' => '{="abc" |> "strtoupper"}',
		'forbidden.latte' => '{="abc" |> "trim"}',
	]));
	Assert::same('abc ABC', $latte->renderToString('allowed'));
	Assert::exception(
		fn() => $latte->renderToString('forbidden'),
		Latte\SecurityViolationException::class,
		'Calling trim() is not allowed.',
	);
});
