<?php

/**
 * Test: Latte\Engine: {extends ...} test VI.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MyLayoutFileFinder implements \Latte\ILayoutFileFinder
{

	public function find()
	{
		return __DIR__ . '/templates/inheritance.parent.latte';
	}

}


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child6.phtml',
	$latte->compile(__DIR__ . '/templates/inheritance.child6.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child5.html',
	$latte->renderToString(
		__DIR__ . '/templates/inheritance.child6.latte',
		array('_layoutFileFinder' => new MyLayoutFileFinder())
	)
);
