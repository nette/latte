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
$latte->addFunction('fnc', 'trim');

$template = <<<'EOD'
{fnc(" abc ")}
EOD;

// compile-time
$latte->compile($template);
Assert::equal(
	[
		'macros' => Expect::type('array'),
		'functions' => ['fnc'],
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
