<?php

/**
 * Test: general HTML test.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
<ul>
	<li>{$hello->{'item'}}</li>
	<li>{function(){}}</li>
	<li>{function(){return}}</li>
	<li>{function() { return; } }</li>
	<li>{function(){return}|upper}</li>
	<li>{function() { return; } |upper}</li>
	<li>{function() { return; } |upper:$item->{10}}</li>
</ul>

EOD;

Assert::matchFile(
	__DIR__ . '/expected/Compiler.recursive.macro.phtml',
	$latte->compile($template)
);
