<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class LogPolicy implements Latte\Policy
{
	public $log = [];


	public function isMacroAllowed(string $macro): bool
	{
		return true;
	}


	public function isFilterAllowed(string $filter): bool
	{
		return true;
	}


	public function isFunctionAllowed(string $function): bool
	{
		return true;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		return true;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		$this->log['property'][] = [$class, $property];
		return true;
	}
}


class MyClass
{
	public static $static = 1;
	public $bar = 1;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

$policy = new LogPolicy;
$latte->setPolicy($policy);
$latte->setSandboxMode();

$template = <<<'EOD'

{var $class = MyClass}
{var $staticProp = 'static'}
{=\MyClass::$static}
{=\MyClass::$$staticProp}
{=$class::$static}
{=$class::$$staticProp}

{var $obj = new MyClass}
{var $prop = bar}
{=$obj -> bar}
{=$obj->$prop}
{=$obj::$$staticProp}

EOD;

$latte->compile($template);
Assert::same(
	[],
	$policy->log
);


$latte->renderToString($template);
Assert::same(
	[
		'property' => [
			['MyClass', 'static'],
			['MyClass', 'static'],
			['MyClass', 'static'],
			['MyClass', 'static'],
			['MyClass', 'bar'],
			['MyClass', 'bar'],
			['MyClass', 'static'],
		],
	],
	$policy->log
);
