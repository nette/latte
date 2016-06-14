<?php

/**
 * Test: {use ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MyMacros extends Latte\Macros\MacroSet
{
	public static function install($compiler)
	{
		$me = new static($compiler);
		$me->addMacro('my', 'echo "ok"');
		return $me;
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{use MyMacros}

{my}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.use.phtml',
	@$latte->compile($template) // @ is deprecated
);
Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.use.html',
	@$latte->renderToString($template)
);
