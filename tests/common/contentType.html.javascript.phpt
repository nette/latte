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
	'Do not place {= 123|noescape} inside quotes in JavaScript (at column 10)',
);

Assert::exception(
	fn() => $latte->compile('<script> "{$var}" </script>'),
	Latte\CompileException::class,
	'Do not place {= $var} inside quotes in JavaScript (at column 11)',
);

Assert::exception(
	fn() => $latte->compile("<script> '{\$var}' </script>"),
	Latte\CompileException::class,
	'Do not place {= $var} inside quotes in JavaScript (at column 11)',
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

Assert::match(
	'<script type="text/html">&lt;&gt;</script>',
	$latte->renderToString('<script type="text/html">{="<>"}</script>'),
);

// content of <script> is RAWTEXT
Assert::match(
	<<<'XX'

			<script type="text/html">
			<div n:foreach="[a, b] as $i">def</div>
			</script>
			<div>a</div>
			<div>b</div>

		XX,
	$latte->renderToString(
		<<<'XX'

				{var $i = def}
				<script type="text/html">
				<div n:foreach="[a, b] as $i">{$i}</div>
				</script>
				<div n:foreach="[a, b] as $i">{$i}</div>

			XX,
	),
);

// content of <script> changed to html
Assert::match(
	<<<'XX'

			<script type="text/html">
			<div>a</div>
			<div>b</div>
			</script>
			<div>a</div>
			<div>b</div>

		XX,
	$latte->renderToString(
		<<<'XX'

				{var $i = def}
				<script type="text/html">
				{contentType html}
				<div n:foreach="[a, b] as $i">{$i}</div>
				</script>
				<div n:foreach="[a, b] as $i">{$i}</div>

			XX,
	),
);

// trim inside <script>
Assert::match(
	'<script>123;</script>',
	$latte->renderToString('<script>{block|trim}  123;  {/block}</script>'),
);
