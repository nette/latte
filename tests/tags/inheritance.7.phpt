<?php

/**
 * Test: {extends ...} and variables
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([

	'parent' => '
{var $varParent = "parent"}

{block title}{/block}

{include content}
',

	'inter' => '
{layout "parent"}
{var $varInter = "inter"}
',

	'main1' => <<<'EOD'
		{layout "parent"}
		{var $varMain = "main"}

		{block title}
		    block: {$varMain} {$varParent ?? undef}
		{/block}

		{block content}
		    include: {$varMain} {$varParent ?? undef}
		{/block}
		EOD
	,

	'main2' => <<<'EOD'
		{layout "inter"}
		{var $varMain = "main"}

		{block title}
		    block: {$varMain} {$varInter ?? undef} {$varParent ?? undef}
		{/block}

		{block content}
		    include: {$varMain} {$varInter ?? undef} {$varParent ?? undef}
		{/block}
		EOD,
]));


Assert::match(
	'

    block: main parent


    include: main undef
',
	$latte->renderToString('main1'),
);


Assert::match(
	'

    block: main inter parent


    include: main undef undef
',
	$latte->renderToString('main2'),
);
