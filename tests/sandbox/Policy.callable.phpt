<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';


class MyClass
{
	public function __call(string $nm, array $args)
	{
	}


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
	{var $class = MyClass}
	{=preg_replace_callback('/./', [$class, 'foo'], 'b')}
	{=preg_replace_callback('/./', [$obj, 'a'], 'b')}

	{=trim(...)::class}
	{=$obj->b(...)::class}
	{=$obj::foo(...)::class}
	EOD;

$latte->compile($template);
Assert::equal(
	[
		'tags' => ['var', '=', '=', '=', '=', '='],
		'functions' => ['preg_replace_callback', 'preg_replace_callback'],
	],
	$policy->log,
);


// run-time
$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template, ['obj' => new MyClass]);
Assert::equal(
	[
		'methods' => [
			['MyClass', 'foo'],
			['MyClass', 'a'],
			['MyClass', 'b'],
			['MyClass', 'foo'],
		],
		'functions' => ['trim'],
	],
	$policy->log,
);
