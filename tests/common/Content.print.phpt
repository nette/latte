<?php declare(strict_types=1);

/**
 * Test: Content is printed unescaped in a context of its content type
 */

use Latte\ContentType;
use Latte\Runtime\Content;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'html' => <<<'X'
		<p>{$c}</p>
		<script>{$c}</script>
		<a onclick="f({$c})"></a>
		<style>{$c}</style>
		<a style="color: {$c}"></a>
		X,
	'js' => '{contentType javascript}f({$c})',
	'css' => '{contentType css}a { color: {$c} }',
	'xml' => '{contentType xml}<a title="{$c}">{$c}</a>',
	'ical' => '{contentType text/calendar}SUMMARY:{$c}',
	'widget' => '{contentType javascript}alert({$var});',
	'widgetBlock' => '<script>{block widget}alert({$var});{/block}</script>',
	'page' => '<script>{$widget}</script>',
]));

$inline = new Latte\Engine;
$inline->setLoader(new Latte\Loaders\StringLoader);


$js = new Content('g("</script>")', ContentType::JavaScript);
Assert::same(<<<'X'
	<p>g("&lt;/script&gt;")</p>
	<script>g("<\/script>")</script>
	<a onclick="f(g(&quot;&lt;\/script&gt;&quot;))"></a>
	<style>g\(\"\<\/script\>\"\)</style>
	<a style="color: g\(\&quot;\&lt;\/script\&gt;\&quot;\)"></a>
	X, $latte->renderToString('html', ['c' => $js]));
Assert::same('f(g("<\/script>"))', $latte->renderToString('js', ['c' => $js]));


$css = new Content('red', ContentType::Css);
Assert::same('<style>red</style>', $inline->renderToString('<style>{$c}</style>', ['c' => $css]));
Assert::same('<a style="color: red"></a>', $inline->renderToString('<a style="color: {$c}"></a>', ['c' => $css]));
Assert::same('a { color: red }', $latte->renderToString('css', ['c' => $css]));
Assert::same('a { color: blue \!important }', $latte->renderToString('css', ['c' => new Content('blue !important', ContentType::Text)]));


$xml = new Content('<b>a &amp; b</b>', ContentType::Xml);
Assert::same('<a title="a &amp; b"><b>a &amp; b</b></a>', $latte->renderToString('xml', ['c' => $xml]));
Assert::same('<p>&lt;b&gt;a &amp;amp; b&lt;/b&gt;</p>', $inline->renderToString('<p>{$c}</p>', ['c' => $xml]));


Assert::same('SUMMARY:a;b', $latte->renderToString('ical', ['c' => new Content('a;b', ContentType::ICal)]));
Assert::same('SUMMARY:a\;b', $latte->renderToString('ical', ['c' => new Content('a;b', ContentType::Text)]));


// rendered template
$widget = $latte->renderToContent('widget', ['var' => '</script>']);
Assert::same('<script>alert("<\/script>");</script>', $latte->renderToString('page', ['widget' => $widget]));

$widget = $latte->renderToContent('widgetBlock', ['var' => '</script>'], 'widget');
Assert::same('<script>alert("<\/script>");</script>', $latte->renderToString('page', ['widget' => $widget]));
