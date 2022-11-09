<?php

/**
 * Test: {ifchanged}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	{foreach [1, 1, 2, 3, 3, 3] as $i} {ifchanged $i} {$i} {/ifchanged} {ifchanged a, b} const {/ifchanged} {/foreach}

	--

	{foreach [1, 1, 2, 3, 3, 3] as $i} {ifchanged $i} {$i} {else} else {/ifchanged} {/foreach}

	--

	{foreach [1, 1, 2, 3, 3, 3] as $i} {ifchanged} -{$i}- {/ifchanged} {/foreach}

	--

	{foreach [1, 1, 2, 3, 3, 3] as $i} {ifchanged} -{$i}- {else} else {/ifchanged} {/foreach}

	--

	{foreach [1, 1, 2, 3, 3, 3] as $i} <span n:ifchanged>{$i}</span> {/foreach}

	--

	{foreach [1, 1, 2, 3, 3, 3] as $i} <span class="{$i}" n:ifchanged></span> {/foreach}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/ifchanged.php',
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		  1   const       2     3

		--

		  1    else    2    3    else    else

		--

		  -1-      -2-    -3-

		--

		  -1-    else    -2-    -3-    else    else

		--

		 <span>1</span>    <span>2</span>  <span>3</span>

		--

		 <span class="1"></span>    <span class="2"></span>  <span class="3"></span>

		XX,
	$latte->renderToString($template),
);
