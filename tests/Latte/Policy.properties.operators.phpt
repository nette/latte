<?php

declare(strict_types=1);

use Tester\Assert;
use Tester\Expect;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';


class MyClass
{
	public static $static = 1;
	public $bar = 1;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setTempDirectory(getTempDir());

$policy = new PolicyLogger;
$latte->setPolicy($policy);
$latte->setSandboxMode();

$template = <<<'EOD'
{var $class = MyClass}
{var $obj = new MyClass}
{var $prop = bar}
{var $staticProp = 'static'}

{=\MyClass::$static ?? null}
{=\MyClass::$$staticProp ?? null}
{=$obj->bar ?? null}
{=$obj->$prop ?? null}
{=$obj::$$staticProp ?? null}

{=$obj?->bar}
{=$obj?->$prop}
{=$obj?::$static}
{=$obj??->bar}
EOD;

@$latte->compile($template);
Assert::equal(
	[
		'macros' => Expect::type('array'),
	],
	$policy->log
);


@$latte->renderToString($template);
Assert::equal(
	[
		'macros' => Expect::type('array'),
		'properties' => [
			['MyClass', 'static'],
			['MyClass', 'static'],
			['MyClass', 'bar'],
			['MyClass', 'bar'],
			['MyClass', 'static'],
			['MyClass', 'bar'],
			['MyClass', 'bar'],
			['MyClass', 'static'],
			['MyClass', 'bar'],
		],
	],
	$policy->log
);
