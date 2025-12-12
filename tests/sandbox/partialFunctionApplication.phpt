<?php

// Partial Function Application in sandbox

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';


class TestClass
{
	public function method(string $a, string $b): string
	{
		return $a . $b;
	}


	public static function staticMethod(string $a, string $b): string
	{
		return $a . $b;
	}
}


$latte = createLatte();
$latte->setTempDirectory(getTempDir());

$policy = new PolicyLogger;
$latte->setPolicy($policy);
$latte->setSandboxMode();


// Test PFA with function
test('PFA with function', function () use ($latte, $policy) {
	$policy->log = [];
	$template = '{var $fn = substr(?, 0, 3)}{=$fn("hello")}';
	$result = $latte->renderToString($template);
	Assert::same('hel', $result);
	Assert::contains('substr', $policy->log['functions'] ?? []);
});


// Test PFA with method
test('PFA with method', function () use ($latte, $policy) {
	$policy->log = [];
	$template = '{var $fn = $obj->method("a", ?)}{=$fn("b")}';
	$result = $latte->renderToString($template, ['obj' => new TestClass]);
	Assert::same('ab', $result);
	Assert::contains(['TestClass', 'method'], $policy->log['methods'] ?? []);
});


// Test PFA with static method
test('PFA with static method', function () use ($latte, $policy) {
	$policy->log = [];
	$template = '{var $fn = TestClass::staticMethod(?, "b")}{=$fn("a")}';
	$result = $latte->renderToString($template);
	Assert::same('ab', $result);
	Assert::contains(['TestClass', 'staticMethod'], $policy->log['methods'] ?? []);
});


// Test PFA with named argument
test('PFA with named argument', function () use ($latte, $policy) {
	$policy->log = [];
	$template = '{var $fn = substr(?, 0, length: 3)}{=$fn("hello")}';
	$result = $latte->renderToString($template);
	Assert::same('hel', $result);
});


// Test PFA with variadic
test('PFA with variadic', function () use ($latte, $policy) {
	$policy->log = [];
	$template = '{var $fn = sprintf("%s-%s", ?, ...)}{=$fn("a", "b")}';
	$result = $latte->renderToString($template);
	Assert::same('a-b', $result);
});
