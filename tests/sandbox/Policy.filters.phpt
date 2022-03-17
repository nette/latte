<?php

declare(strict_types=1);

use Tester\Assert;
use Tester\Expect;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';


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
$latte->addFilter('callable', fn() => '');

$template = <<<'EOD'
	{var $var = ("xxx"|upper|truncate:10)}
	{$var|lower|truncate:20}
	{$var|callable:[MyClass, foo]}

	EOD;

// compile-time
$latte->compile($template);
Assert::equal(
	[
		'tags' => Expect::type('array'),
		'filters' => ['upper', 'truncate', 'lower', 'truncate', 'callable'],
	],
	$policy->log,
);


// run-time
$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template);
Assert::same(
	[
		'methods' => [['MyClass', 'foo']],
	],
	$policy->log,
);
