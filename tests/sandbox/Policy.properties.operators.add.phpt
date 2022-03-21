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

	{=\MyClass::$static++}
	{=$obj->bar++}
	{=$obj->$prop++}
	EOD;

$obj = new MyClass;
$latte->renderToString($template, ['obj' => $obj]);

Assert::same(3, $obj->bar);
Assert::same(2, MyClass::$static);
