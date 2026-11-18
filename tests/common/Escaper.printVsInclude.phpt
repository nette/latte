<?php declare(strict_types=1);

/**
 * Test: printing a string {$x} agrees with including a text template {include} in every context.
 */

use Latte\RuntimeException;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$values = [
	'</script><script>alert(1)</script>',
	'"><img src=x onerror=alert(1)>',
	"' \" & < > {{x}} --> ]]> </style> </textarea> \\ \n",
	'',
	'0',
];

// [context exported by the compiler, snippet]; a whole attribute value is a dynamic attribute, hence the prefix 'a'
$agreeing = [
	['html', '<p>%s</p>'],
	['html/attr', '<p title="a%s">'],
	['html/attr/js', '<p onclick="f(%s)">'],
	['html/raw/js', '<script>x = %s</script>'],
	['html/rcdata', '<textarea>%s</textarea>'],
	['html/comment', '<!-- %s -->'],
	['html/attr/html', '<iframe srcdoc="a%s">'],
	['xml', '{contentType application/xml}<p>%s</p>'],
	['xml/attr', '{contentType application/xml}<p title="a%s"/>'],
	['xml/comment', '{contentType application/xml}<!-- %s -->'],
	['js', '{contentType application/javascript}x = %s'],
];

// Known differences: a text template cannot be included there, while a print escapes.
// The first of $values printed gives the recorded output.
$different = [
	['html/attr/css', '<p style="a: %s">', '<p style="a: \\&lt;\\/script\\&gt;\\&lt;script\\&gt;alert\\(1\\)\\&lt;\\/script\\&gt;">'],
	['html/raw/css', '<style>a { b: %s }</style>', '<style>a { b: \\<\\/script\\>\\<script\\>alert\\(1\\)\\<\\/script\\> }</style>'],
	['css', '{contentType text/css}a { b: %s }', 'a { b: \\<\\/script\\>\\<script\\>alert\\(1\\)\\<\\/script\\> }'],
	[
		'html/raw/html',
		'<script type="text/html">%s</script>',
		'<script type="text/html">&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;</script>',
	],
	['html/tag', '<p %s>', '<p &lt;&#47;script&gt;&lt;script&gt;alert(1)&lt;&#47;script&gt;>'],
];


function renderPrint(string $snippet, string $x): string
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['print' => str_replace('%s', '{$x}', $snippet)]));
	return $latte->renderToString('print', ['x' => $x]);
}


function renderInclude(string $snippet, string $x): string
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'text.latte' => '{contentType text}{$x}',
		'include' => str_replace('%s', "{include 'text.latte'}", $snippet),
	]));
	return $latte->renderToString('include', ['x' => $x]);
}


function compilePrint(string $snippet): string
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['print' => str_replace('%s', '{$x}', $snippet)]));
	return $latte->compile('print');
}


foreach ($agreeing as [$context, $snippet]) {
	Assert::contains("convertTrusted(\$ʟ_val, '$context')", compilePrint($snippet), $context);
	foreach ($values as $x) {
		Assert::same(renderPrint($snippet, $x), renderInclude($snippet, $x), "$context: " . json_encode($x));
	}
}


foreach ($different as [$context, $snippet, $printed]) {
	Assert::contains("convertTrusted(\$ʟ_val, '$context')", compilePrint($snippet), $context);
	Assert::same($printed, renderPrint($snippet, $values[0]), $context);
	foreach ($values as $x) {
		Assert::exception(
			fn() => renderInclude($snippet, $x),
			RuntimeException::class,
			"Including 'text.latte' with content type TEXT into incompatible type " . strtoupper($context) . '.',
		);
	}
}
