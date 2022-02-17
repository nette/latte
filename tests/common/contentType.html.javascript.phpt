<?php

/**
 * Test: Latte\Engine and JavaScript in HTML.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<script>"<>"',
	$latte->renderToString('<script>{="<>"}')
);

Assert::match(
	'<script>"<\/" "]]\u003E" "\u003C!"',
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

Assert::exception(function () use ($latte) {
	$latte->compile('<script>"{=123|noescape}"');
}, Latte\CompileException::class, 'Do not place {=123|noescape} inside quotes in JavaScript.');

Assert::match(
	'<script id="&lt;&gt;">',
	$latte->renderToString('<script id="{="<>"}">')
);

Assert::exception(function () use ($latte) {
	$latte->compile('<script> "{$var}" </script>');
}, Latte\CompileException::class, 'Do not place {$var} inside quotes in JavaScript.');

Assert::exception(function () use ($latte) {
	$latte->compile("<script> '{\$var}' </script>");
}, Latte\CompileException::class, 'Do not place {$var} inside quotes in JavaScript.');

Assert::match(
	'<script type="TEXT/X-JAVASCRIPT">"<>"',
	$latte->renderToString('<script type="TEXT/X-JAVASCRIPT">{="<>"}')
);

Assert::match(
	'<script type="module">"<>"',
	$latte->renderToString('<script type="module">{="<>"}')
);

Assert::match(
	'<script type="text/plain">"<>"',
	$latte->renderToString('<script type="text/plain">{="<>"}')
);

Assert::match(
	'<script type="application/json">{ foo:"<>" }',
	$latte->renderToString('<script type="application/json">{ foo:{="<>"} }')
);

Assert::match(
	'<script type="text/html">&lt;&gt;',
	$latte->renderToString('<script type="text/html">{="<>"}')
);

// content of <script> is RAWTEXT
Assert::match(
	'
	<script type="text/html">
	<div n:foreach="[a, b] as $i">def</div>
	</script>
	<div>a</div>
	<div>b</div>
',
	$latte->renderToString('
	{var $i = def}
	<script type="text/html">
	<div n:foreach="[a, b] as $i">{$i}</div>
	</script>
	<div n:foreach="[a, b] as $i">{$i}</div>
')
);

// content of <script> changed to html
Assert::match(
	'
	<script type="text/html">
	<div>a</div>
	<div>b</div>
	</script>
	<div>a</div>
	<div>b</div>
',
	$latte->renderToString('
	{var $i = def}
	<script type="text/html">
	{contentType html}
	<div n:foreach="[a, b] as $i">{$i}</div>
	</script>
	<div n:foreach="[a, b] as $i">{$i}</div>
')
);

// trim inside <script>
Assert::match(
	'<script>123;</script>',
	$latte->renderToString('<script>{block|trim}  123;  {/block}</script>')
);
