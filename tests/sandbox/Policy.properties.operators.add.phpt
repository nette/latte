<?php

declare(strict_types=1);

use Tester\Assert;

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
	{var $prop = bar}
	{var $staticProp = 'static'}

	{=\MyClass::$static++}
	{=\MyClass::$$staticProp++}
	{=$obj->bar++}
	{=$obj->$prop++}
	{=$obj::$$staticProp++}
	EOD;

$obj = new MyClass;
$latte->renderToString($template, ['obj' => $obj]);

Assert::same(3, $obj->bar);
Assert::same(4, MyClass::$static);
