<?php

/**
 * Test: {cycle}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{foreach range(1, 5) as $i}  {cycle a, b, c}  {/foreach}


{foreach range(1, 5) as $i}
	{continueIf $i % 2}
	{cycle a, b, c}
{/foreach}


{foreach range(1, 5) as $i}  {cycle $i, b, c}  {/foreach}

EOD;

/*Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.cycle.phtml',
	$latte->compile($template)
);*/

Assert::match(
	'
  a    b    c    a    b


	b
	a


  1    b    c    4    b',
	$latte->renderToString($template)
);
