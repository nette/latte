<?php

declare(strict_types=1);

use Tester\Assert;


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
{var $var = 10}
{$var}
{foreach [] as $item}
{/foreach}

EOD;

// compile-time
$latte->compile($template);
Assert::same(
	[
		'macros' => ['var', '=', 'foreach'],
	],
	$policy->log
);


// run-time
$latte->warmupCache($template);
$policy->log = [];
$latte->renderToString($template);
Assert::same(
	[],
	$policy->log
);
