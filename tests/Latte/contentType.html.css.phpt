<?php

/**
 * Test: Latte\Engine and CSS in HTML.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<style>\<\>',
	$latte->renderToString('<style>{="<>"}')
);

Assert::match(
	'<style>\<\/ \]\]\> \<\!',
	$latte->renderToString('<style>{="</"} {="]]>"} {="<!"}')
);

Assert::match(
	'<style></style>&lt;&gt;',
	$latte->renderToString('<style></style>{="<>"}')
);

Assert::match(
	'<style>123',
	$latte->renderToString('<style>{=123}')
);

Assert::match(
	'<style id="&lt;&gt;">',
	$latte->renderToString('<style id="{="<>"}">')
);

Assert::match(
	'<style type="TEXT/CSS">\<\>',
	$latte->renderToString('<style type="TEXT/CSS">{="<>"}')
);

Assert::match(
	'<style type="text/html">&lt;&gt;',
	$latte->renderToString('<style type="text/html">{="<>"}')
);

Assert::match(
	'<style type="text/html">&lt;&gt;',
	$latte->renderToString('<style type="text/html">{="<>"}')
);

Assert::match(
	'<style> a { background: url("\"") } </style>',
	$latte->renderToString('<style> a { background: url("{=\'"\'}") } </style>')
);
