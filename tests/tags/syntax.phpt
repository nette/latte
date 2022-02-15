<?php

/**
 * Test: {syntax ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
<ul n:syntax="double">
{{foreach $people as $person}}
	<li>{{$person |upper}}</li>
{{/foreach}}
{* comment latte *}
{{* comment double *}}
</ul>

<p>Default: {$person}</p>

<p n:syntax="latte">Default: {$person}</p>

<p n:syntax="off">Default: {$person}</p>

{syntax off}
{a}
{ {/ {/syntax
{/syntax}
EOD;

Assert::matchFile(
	__DIR__ . '/expected/syntax.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/syntax.html',
	$latte->renderToString($template, ['people' => ['John', 'Mary', 'Paul']])
);
