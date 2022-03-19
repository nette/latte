<?php

declare(strict_types=1);

use Tester\Assert;
use Tester\Expect;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';
require __DIR__ . '/Policy.fixtures.php';


class MyClass
{
	public static function foo()
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

$policy = new PolicyLogger;
$latte->setPolicy($policy);
$latte->setSandboxMode();

$template = <<<'EOD'
	{var $trim = trim}

	{trim('a')}
	{\trim('a')}
	{ns\test('a')}
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

	{=$obj -> foo()}
	{=$obj->$method()}
	{=[$obj, $method]()}

	EOD;

// compile-time
$latte->compile($template);
Assert::equal(
	[
		'tags' => Expect::type('array'),
		'functions' => ['trim', '\\trim', 'ns\\test'],
	],
	$policy->log,
);


// run-time
$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template, ['obj' => new MyClass]);
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
	$policy->log,
);
