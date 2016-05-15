<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class NoPolicy implements Latte\Policy
{
	public function isMacroAllowed(string $macro): bool
	{
		return $macro === '=';
	}


	public function isFilterAllowed(string $filter): bool
	{
		return false;
	}


	public function isFunctionAllowed(string $function): bool
	{
		return false;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		return false;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		return false;
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy(new NoPolicy);

Assert::noError(function () use ($latte) {
	$latte->compile('{var $abc}');
	$latte->renderToString('{="trim"("hello")}');
});


$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{var $abc}');
}, Latte\CompileException::class, 'Macro {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{="trim"("hello")}');
}, Latte\SecurityViolation::class, 'Calling trim() is not allowed.');
