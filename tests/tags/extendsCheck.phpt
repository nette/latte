<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = '
{extends layout.latte}

{capture $foo}
    {block bar}{/block}
{/capture}

{block content}
content
';

Assert::matchFile(
	__DIR__ . '/expected/extendsCheck.phtml',
	$latte->compile($template)
);
