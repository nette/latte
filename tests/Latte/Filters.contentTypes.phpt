<?php

/**
 * Test: Latte\Filters content type
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::error(function () use ($latte) {
	$latte->renderToString('{define title} <h1>title</h1> {/define}  {include title|upper}');
}, E_USER_WARNING, 'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.');

Assert::error(function () use ($latte) {
	$latte->renderToString('{block|upper} <h1>title</h1> {/block}');
}, E_USER_WARNING, 'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.');

Assert::error(function () use ($latte) {
	$latte->renderToString('{capture $var|upper} <h1>title</h1> {/capture}');
}, E_USER_WARNING, 'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.');

Assert::same(
	' title ',
	$latte->renderToString('{block|striptags} <h1>title</h1> {/block}')
);

Assert::error(function () use ($latte) {
	$latte->renderToString('{block|striptags|upper} <h1>title</h1> {/block}');
}, E_USER_WARNING, 'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.');

Assert::same(
	' title ',
	$latte->renderToString('{block|striphtml} <h1>title</h1> {/block}')
);

Assert::same(
	' TITLE ',
	$latte->renderToString('{block|striphtml|upper} <h1>title</h1> {/block}')
);

Assert::same(
	' ONE &lt; TWO ',
	$latte->renderToString('{block|striphtml|upper} one < two {/block}')
);

Assert::same(
	' one &amp; two ',
	$latte->renderToString('{block|striptags} one &amp; two {/block}')
);
