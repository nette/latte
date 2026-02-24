<?php declare(strict_types=1);

/**
 * Test: Unknown type of <script>
 */

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

// escaping of string
Assert::match(
	'<script type="foo"> <div title=\'\' <\/script>\'>  <\/script> </div> </script>',
	$latte->renderToString('<script type="foo"> <div title=\'{="\' </script>"}\'>  {="</script>"} </div> </script>'),
);

// escaping of Html object
Assert::match(
	'<script type="foo"> <div title=\'<\/script>\'></div> </script>',
	$latte->renderToString(
		'<script type="foo"> {$foo} </script>',
		['foo' => new Html("<div title='</script>'></div>")],
	),
);

// include
Assert::exception(
	fn() => $latte->renderToString('{define a}<script></script>{/define} <script type="foo">{include a}</script>'),
	Latte\RuntimeException::class,
	'Including block a with content type HTML into incompatible type HTML/RAW/TEXT.',
);

// no escape
Assert::match(
	'<script type="foo"><\/script></script>',
	$latte->renderToString('<script type="foo">{="</script>"|noescape}</script>'),
);

// content of <script> is RAWTEXT
Assert::match(
	<<<'XX'
			<script type="foo">
			<div n:foreach="[a, b] as $i">def</div>
			</script>
			<div>a</div>
			<div>b</div>

		XX,
	$latte->renderToString(
		<<<'XX'

				{var $i = def}
				<script type="foo">
				<div n:foreach="[a, b] as $i">{$i}</div>
				</script>
				<div n:foreach="[a, b] as $i">{$i}</div>

			XX,
	),
);

// content of <script> changed to html
Assert::match(
	<<<'XX'
			<script type="foo">
			<div>a</div>
			<div>b</div>
			</script>
			<div>a</div>
			<div>b</div>

		XX,
	$latte->renderToString(
		<<<'XX'

				{var $i = def}
				<script type="foo">
				{contentType html}
				<div n:foreach="[a, b] as $i">{$i}</div>
				</script>
				<div n:foreach="[a, b] as $i">{$i}</div>

			XX,
	),
);
