<?php

/**
 * Test: JavaScript in HTML
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<script>"<>"</script>',
	$latte->renderToString('<script>{="<>"}</script>'),
);

Assert::match(
	'<script>"<\/" "]]\u003E" "\u003C!" </script>',
	$latte->renderToString('<script>{="</"} {="]]>"} {="<!"} </script>'),
);

Assert::match(
	'<script></script>&lt;&gt;',
	$latte->renderToString('<script></script>{="<>"}'),
);

Assert::match(
	'<script>123</script>',
	$latte->renderToString('<script>{=123}</script>'),
);

Assert::match(
	'<script>[1,2,3]</script>',
	$latte->renderToString('<script>{=[1,2,3]}</script>'),
);

Assert::exception(
	fn() => $latte->compile('<script>"{=123|noescape}"'),
	Latte\CompileException::class,
	'Do not place {=123|noescape} inside quotes in JavaScript (on line 1 at column 10)',
);

Assert::exception(
	fn() => $latte->compile('<script> "{$var}" </script>'),
	Latte\CompileException::class,
	'Do not place {=$var} inside quotes in JavaScript (on line 1 at column 11)',
);

Assert::exception(
	fn() => $latte->compile("<script> '{\$var}' </script>"),
	Latte\CompileException::class,
	'Do not place {=$var} inside quotes in JavaScript (on line 1 at column 11)',
);

Assert::match(
	'<script id="&lt;&gt;"></script>',
	$latte->renderToString('<script id="{="<>"}"></script>'),
);

Assert::match(
	'<script type="TEXT/X-JAVASCRIPT">"<>"</script>',
	$latte->renderToString('<script type="TEXT/X-JAVASCRIPT">{="<>"}</script>'),
);

Assert::match(
	'<script type="module">"<>"</script>',
	$latte->renderToString('<script type="module">{="<>"}</script>'),
);

Assert::match(
	'<script type="text/plain">"<>"</script>',
	$latte->renderToString('<script type="text/plain">{="<>"}</script>'),
);

Assert::match(
	'<script type="application/json">{ foo:"<>" }</script>',
	$latte->renderToString('<script type="application/json">{ foo:{="<>"} }</script>'),
);

// trim inside <script>
Assert::match(
	'<script>123;</script>',
	$latte->renderToString('<script>{block|trim}  123;  {/block}</script>'),
);
