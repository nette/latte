<?php

/**
 * Test: {foreach}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{foreach [a, b] as $item}
	item
{/foreach}

---

{foreach [a, b] as $item}
	{$iterator->counter}
{/foreach}
{!$iterator ? 'empty'}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.foreach2.phtml',
	$latte->compile($template)
);

Assert::match(
	'
	item
	item

---

	1
	2
empty
',
	$latte->renderToString($template)
);
