<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
{var $var = 10}
Outer text

{define test}
	This is definition #{$var}
{/define}
EOD;

Assert::match(
	'This is definition #5',
	trim($latte->renderToString($template, ['var' => 5], 'test'))
);

$template = <<<'EOD'
Outer text
{define testargs $var1, $var2}
	Variables {$var1}, {$var2}
{/define}
EOD;

Assert::match(
	'Variables 5, 6',
	trim($latte->renderToString($template, [5, 6], 'testargs'))
);



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => '<title>{block title}My website{/block}</title>',

	'main' => '
{extends "parent"}
{block title}Homepage | {include parent}{/block}
	',
]));

Assert::match(
	'Homepage | My website',
	$latte->renderToString('main', [], 'title')
);
