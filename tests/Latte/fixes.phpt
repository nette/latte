<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match( // fix #58
	'x',
	$latte->renderToString('{contentType application/xml}{if TRUE}x{/if}')
);

Assert::match(
	'<a href=""></a>',
	$latte->renderToString('<a href="{ifset $x}{$x}{/ifset}"></a>')
);
