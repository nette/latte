<?php

/**
 * Test: Latte\Engine: {use ...}
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

Assert::matchFile(
	__DIR__ . '/expected/macros.use.phtml',
	$latte->compile(__DIR__ . '/templates/use.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.use.html',
	$latte->renderToString(__DIR__ . '/templates/use.latte')
);
