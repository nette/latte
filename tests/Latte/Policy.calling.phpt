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
		$this->log['function'][] = $function;
		return true;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		$this->log['method'][] = [$class, $method];
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
	public static function foo()
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

$policy = new LogPolicy;
$latte->setPolicy($policy);
$latte->setSandboxMode();

$template = <<<'EOD'
{var $trim = trim}

{trim('a')}
{='trim'('a')}
{$trim('a')}

{var $class = MyClass}
{var $method = foo}
{=MyClass::foo()}
{=\MyClass::$method()}
{=$class::foo()}
{=$class::$method()}
{="$class::foo"()}
{=[$class, 'foo']()}

{var $obj = new MyClass}
{=$obj -> foo()}
{=$obj->$method()}
{=[$obj, $method]()}

EOD;

$latte->compile($template);
Assert::same(
	[
		'function' => ['trim'],
	],
	$policy->log
);


$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template);
Assert::same(
	[
		'function' => ['trim', 'trim'],
		'method' => [
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
			['MyClass', 'foo'],
		],
	],
	$policy->log
);
