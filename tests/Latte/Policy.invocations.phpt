<?php

declare(strict_types=1);

use Tester\Assert;
use Tester\Expect;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.LogPolicy.php';


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

// compile-time
$latte->compile($template);
Assert::equal(
	[
		'macros' => Expect::type('array'),
		'functions' => ['trim'],
	],
	$policy->log
);


// run-time
$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template);
Assert::same(
	[
		'functions' => ['trim', 'trim'],
		'methods' => [
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
