<?php

/**
 * Test: HTML in <script>
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

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
