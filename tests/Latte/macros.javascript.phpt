<?php

/**
 * Test: Latte\Engine and JavaScript.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<script>"<>"',
	$latte->renderToString('<script>{="<>"}')
);

Assert::match(
	'<script>"<\/" "]]\x3E" "\x3C!"',
	$latte->renderToString('<script>{="</"} {="]]>"} {="<!"}')
);

Assert::match(
	'<script></script>&lt;&gt;',
	$latte->renderToString('<script></script>{="<>"}')
);

Assert::match(
	'<script>123',
	$latte->renderToString('<script>{=123}')
);

Assert::match(
	'<script>[1,2,3]',
	$latte->renderToString('<script>{=[1,2,3]}')
);

Assert::match(
	'<script>"123"',
	$latte->renderToString('<script>"{=123|noescape}"')
);

Assert::match(
	'<script id="&lt;&gt;">',
	$latte->renderToString('<script id="{="<>"}">')
);

Assert::exception(function() use ($latte) {
	$latte->compile('<script> "{$var}" </script>');
}, 'Latte\CompileException', 'Do not place {$var} inside quotes.');

Assert::exception(function() use ($latte) {
	$latte->compile("<script> '{\$var}' </script>");
}, 'Latte\CompileException', 'Do not place {$var} inside quotes.');

Assert::match(
	'<script type="TEXT/X-JAVASCRIPT">"<>"',
	$latte->renderToString('<script type="TEXT/X-JAVASCRIPT">{="<>"}')
);

Assert::match(
	'<script type="text/html">&lt;&gt;',
	$latte->renderToString('<script type="text/html">{="<>"}')
);
