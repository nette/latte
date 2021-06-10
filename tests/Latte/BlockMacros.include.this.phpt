<?php

/**
 * Test: {include this}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// out of block
Assert::exception(function () use ($latte) {
	$latte->renderToString('{include this}');
}, Latte\CompileException::class, 'Cannot include this block outside of any block.');

// in snippet block
Assert::exception(function () use ($latte) {
	$latte->renderToString('{snippet foo} {include this} {/snippet}');
}, Latte\CompileException::class, 'Cannot include this block outside of any block.');

// with modifier
Assert::match(
	'  2 1',
	$latte->renderToString('{block foo}  {$i--} {if $i}{include this|trim}{/if}  {/block}', ['i' => 2])
);

// with params
Assert::match(
	' 2  -1',
	$latte->renderToString('{block foo} {$i--} {if $i > 0}{include this, i: $i - 2}{/if} {/block}', ['i' => 2])
);

// double
Assert::match(
	' 2  1     1',
	$latte->renderToString('{block foo} {$i--} {if $i}{include this}{/if} {if $i}{include this}{/if} {/block}', ['i' => 2])
);
