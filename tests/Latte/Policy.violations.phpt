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
$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{var $abc}');
}, Latte\CompileException::class, 'Macro {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('{$abc|upper}');
}, Latte\CompileException::class, 'Filter |upper is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('{trim(123)}');
}, Latte\CompileException::class, 'Function trim() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{="trim"(123)}');
}, Latte\SecurityViolation::class, 'Calling trim() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->error(123)}', ['obj' => new stdClass]);
}, Latte\SecurityViolation::class, 'Calling stdClass::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=[$obj, "error"](123)}', ['obj' => new stdClass]);
}, Latte\SecurityViolation::class, 'Calling stdClass::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->error}', ['obj' => new stdClass]);
}, Latte\SecurityViolation::class, "Access to 'error' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->$prop}', ['obj' => new stdClass, 'prop' => 'error']);
}, Latte\SecurityViolation::class, "Access to 'error' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj::$prop}', ['obj' => new stdClass]);
}, Latte\SecurityViolation::class, "Access to 'prop' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->compile('{$this->filters}');
}, Latte\CompileException::class, 'Forbidden variable $this.');
