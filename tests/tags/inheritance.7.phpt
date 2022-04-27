<?php

/**
 * Test: {extends ...} and variables
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([

	'parent' => <<<'XX'

		{var $varParent = "parent"}

		{block title}{/block}

		{include content}

		XX,

	'inter' => <<<'XX'

		{layout "parent"}
		{var $varInter = "inter"}

		XX,

	'main1' => '{layout "parent"}
{var $varMain = "main"}

{block title}
    block: {$varMain} {$varParent ?? undef}
{/block}

{block content}
    include: {$varMain} {$varParent ?? undef}
{/block}',

	'main2' => '{layout "inter"}
{var $varMain = "main"}

{block title}
    block: {$varMain} {$varInter ?? undef} {$varParent ?? undef}
{/block}

{block content}
    include: {$varMain} {$varInter ?? undef} {$varParent ?? undef}
{/block}',
]));


Assert::match(
	<<<'XX'

		    block: main parent

		    include: main undef

		XX,
	$latte->renderToString('main1'),
);


Assert::match(
	<<<'XX'

		    block: main inter parent

		    include: main undef undef

		XX,
	$latte->renderToString('main2'),
);
