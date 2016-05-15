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
	__DIR__ . '/expected/macros.use.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/macros.use.html',
	$latte->renderToString($template)
);
