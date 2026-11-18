<?php declare(strict_types=1);

/**
 * Test: Engine::renderToTrusted() returns the output as an object of the content type it really has
 */

use Latte\Runtime\{TrustedCss, TrustedHtml, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'html' => '<p title="{$x}">{$x}</p>',
	'xml' => '{contentType xml}<a b="{$x}">{$x}</a>',
	'js' => '{contentType application/javascript}var a = {$x};',
	'css' => '{contentType css}a { content: {$x} }',
	'text' => '{contentType text}{$x}',
	'ical' => '{contentType text/calendar}{$x}',

	'layout-xml' => '{contentType xml}<root>{block content}{/block}</root>',
	'layout-html' => '<div>{block content}{/block}</div>',
	'html-in-xml' => '{layout "layout-xml"}{block content}<i>{$x}</i>{/block}',
	'xml-in-html' => '{contentType xml}{layout "layout-html"}{block content}<i>{$x}</i>{/block}',
	'variable' => '{extends $layout}{block content}<i>{$x}</i>{/block}',
	'none' => '{extends none}<i>{$x}</i>',
	'plain' => '<i>{$x}</i>',

	'blocks' => <<<'X'
		{define text}<b>{$x}</b>{/define}
		<script>{block js}var s = {$x};{/block}</script>
		<style>{block css}a { content: {$x} }{/block}</style>
		<title>{block title}{$x}{/block}</title>
		<textarea>{block textarea}{$x}{/block}</textarea>
		<p title="{block attr}{$x}{/block}"></p>
		<iframe srcdoc="{block srcdoc}<b>{$x}</b>{/block}"></iframe>
		<!-- {block comment}{$x}{/block} -->
		X,
	'xml-blocks' => '{contentType xml}<r a="{block attr}{$x}{/block}">{block text}<i>{$x}</i>{/block}</r>',
	'layout-title' => '<title>{block title}default{/block}</title>',
	'override' => '{layout "layout-title"}{block title}<b>{$x}</b>{/block}',

	'throws' => '<p>{block a}{$fail()}{/block}</p>',
]));

$printer = new Latte\Engine;
$printer->setLoader(new Latte\Loaders\StringLoader);

$x = '"</b><img src=x onerror=alert(1)> & &quot; < {{a}}';


function assertTrusted(string $class, string $expected, object $res): void
{
	Assert::type($class, $res);
	Assert::same($expected, (string) $res);
}


// template types
assertTrusted(TrustedHtml::class, $latte->renderToString('html', ['x' => $x]), $latte->renderToTrusted('html', ['x' => $x]));
assertTrusted(TrustedXml::class, $latte->renderToString('xml', ['x' => $x]), $latte->renderToTrusted('xml', ['x' => $x]));
assertTrusted(TrustedJavaScript::class, $latte->renderToString('js', ['x' => $x]), $latte->renderToTrusted('js', ['x' => $x]));
assertTrusted(TrustedCss::class, $latte->renderToString('css', ['x' => $x]), $latte->renderToTrusted('css', ['x' => $x]));

Assert::exception(
	fn() => $latte->renderToTrusted('text', ['x' => $x]),
	Latte\RuntimeException::class,
	"Cannot render 'text' with content type TEXT as trusted content, use renderToString().",
);
Assert::exception(
	fn() => $latte->renderToTrusted('ical', ['x' => $x]),
	Latte\RuntimeException::class,
	"Cannot render 'ical' with content type ICAL as trusted content, use renderToString().",
);


// the type comes from the last template of the {extends} chain
Assert::error(
	fn() => assertTrusted(TrustedXml::class, '<root><i>"&lt;/b&gt;</i></root>', $latte->renderToTrusted('html-in-xml', ['x' => '"</b>'])),
	E_USER_DEPRECATED,
	'Converting content type XML into HTML is deprecated, XML and HTML will be incompatible.',
);
Assert::error(
	fn() => assertTrusted(
		TrustedHtml::class,
		'<div><i>&quot;&lt;/b&gt;</i></div>',
		$latte->renderToTrusted('xml-in-html', ['x' => '"</b>']),
	),
	E_USER_DEPRECATED,
	'Converting content type HTML into XML is deprecated, XML and HTML will be incompatible.',
);
Assert::error(
	fn() => Assert::type(TrustedXml::class, $latte->renderToTrusted('variable', ['x' => '', 'layout' => 'layout-xml'])),
	E_USER_DEPRECATED,
);
Assert::type(TrustedHtml::class, $latte->renderToTrusted('variable', ['x' => '', 'layout' => 'layout-html']));

$finder = new Latte\Engine;
$finder->setLoader($latte->getLoader());
$finder->addProvider('coreParentFinder', fn() => 'layout-xml');
assertTrusted(TrustedXml::class, '<root></root>', $finder->renderToTrusted('plain', ['x' => '']));
assertTrusted(TrustedHtml::class, '<i>&lt;</i>', $finder->renderToTrusted('none', ['x' => '<']));


// a single block has the content type of the place where it is defined
assertTrusted(
	TrustedHtml::class,
	'<b>"&lt;/b&gt;&lt;img src=x onerror=alert(1)&gt; &amp; &amp;quot; &lt; {<!-- -->{a}}</b>',
	$latte->renderToTrusted('blocks', ['x' => $x], 'text'),
);
assertTrusted(
	TrustedJavaScript::class,
	'var s = "\"<\/b><img src=x onerror=alert(1)> & &quot; < {{a}}";',
	$latte->renderToTrusted('blocks', ['x' => $x], 'js'),
);
assertTrusted(
	TrustedCss::class,
	$latte->renderToString('blocks', ['x' => $x], 'css'),
	$latte->renderToTrusted('blocks', ['x' => $x], 'css'),
);
assertTrusted(
	TrustedHtml::class,
	'"&lt;/b&gt;&lt;img src=x onerror=alert(1)&gt; &amp; &amp;quot; &lt; {<!-- -->{a}}',
	$latte->renderToTrusted('blocks', ['x' => $x], 'title'),
);
assertTrusted(
	TrustedHtml::class,
	'"&lt;/b&gt;&lt;img src=x onerror=alert(1)&gt; &amp; &amp;quot; &lt; {<!-- -->{a}}',
	$latte->renderToTrusted('blocks', ['x' => $x], 'textarea'),
);
assertTrusted(
	TrustedHtml::class,
	'"&lt;/b&gt;&lt;img src=x onerror=alert(1)&gt; &amp; &amp;quot; &lt; {<!-- -->{a}}',
	$latte->renderToTrusted('blocks', ['x' => $x], 'attr'),
);
assertTrusted(
	TrustedHtml::class,
	'<b>&quot;&lt;/b&gt;&lt;img src=x onerror=alert(1)&gt; &amp; &amp;quot; &lt; {<!-- -->{a}}</b>',
	$latte->renderToTrusted('blocks', ['x' => $x], 'srcdoc'),
);
Assert::exception(
	fn() => $latte->renderToTrusted('blocks', ['x' => $x], 'comment'),
	Latte\RuntimeException::class,
	'Cannot render block comment with content type HTML/COMMENT as HTML.',
);
assertTrusted(TrustedXml::class, '&quot;&lt;/b&gt;', $latte->renderToTrusted('xml-blocks', ['x' => '"</b>'], 'attr'));
assertTrusted(TrustedXml::class, '<i>&quot;&lt;/b&gt;</i>', $latte->renderToTrusted('xml-blocks', ['x' => '"</b>'], 'text'));
Assert::exception(
	fn() => $latte->renderToTrusted('blocks', ['x' => $x], 'undefined'),
	Latte\RuntimeException::class,
	"Cannot include undefined block 'undefined'.",
);

// an overriding block keeps its own content type
assertTrusted(TrustedHtml::class, '<b>&lt;</b>', $latte->renderToTrusted('override', ['x' => '<'], 'title'));
assertTrusted(TrustedHtml::class, '<title><b>&lt;</b></title>', $latte->renderToTrusted('override', ['x' => '<']));


// hostile content of a block from <script> is code in <script> and text elsewhere
$js = $latte->renderToTrusted('blocks', ['x' => '"</b><img src=x onerror=alert(1)>'], 'js');
Assert::same(
	str_replace("\r\n", "\n", <<<'X'
		<p>var s = "\"&lt;\/b&gt;&lt;img src=x onerror=alert(1)&gt;";</p>
		<script>var s = "\"<\/b><img src=x onerror=alert(1)>";</script>
		<p title="var s = &quot;\&quot;&lt;\/b&gt;&lt;img src=x onerror=alert(1)&gt;&quot;;"></p>
		X),
	$printer->renderToString(
		<<<'X'
			<p>{$value}</p>
			<script>{$value}</script>
			<p title="{$value}"></p>
			X,
		['value' => $js],
	),
);

// a block from an attribute becomes HTML text of the same meaning
$attr = $latte->renderToTrusted('blocks', ['x' => 'a &quot; < "b"'], 'attr');
Assert::same('a &amp;quot; &lt; "b"', (string) $attr);
Assert::same('<p>a &amp;quot; &lt; "b"</p>', $printer->renderToString('<p>{$value}</p>', ['value' => 'a &quot; < "b"']));


// round trip: printed into its own context it equals the direct output
Assert::same(
	$latte->renderToString('html', ['x' => $x]),
	$printer->renderToString('{$value}', ['value' => $latte->renderToTrusted('html', ['x' => $x])]),
);
Assert::same(
	'<script>' . $latte->renderToString('blocks', ['x' => $x], 'js') . '</script>',
	$printer->renderToString('<script>{$value}</script>', ['value' => $latte->renderToTrusted('blocks', ['x' => $x], 'js')]),
);
Assert::same(
	'<p title="' . $latte->renderToString('blocks', ['x' => $x], 'attr') . '"></p>',
	$printer->renderToString('<p title="{$value}"></p>', ['value' => $latte->renderToTrusted('blocks', ['x' => $x], 'attr')]),
);
Assert::same(
	$latte->renderToString('xml', ['x' => $x]),
	$printer->renderToString('{contentType xml}{$value}', ['value' => $latte->renderToTrusted('xml', ['x' => $x])]),
);


// rendering that throws leaves no open output buffer
$level = ob_get_level();
Assert::exception(
	fn() => $latte->renderToTrusted('throws', ['fail' => fn() => throw new Exception('foo')]),
	Exception::class,
	'foo',
);
Assert::same($level, ob_get_level());
