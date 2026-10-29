<?php declare(strict_types=1);

use Latte\ContentType;
use Latte\Runtime\{Content, Html};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'html' => '<b>{$var}</b>',
	'mimeHtml' => '{contentType text/html}<b>{$var}</b>',
	'text' => '{contentType text}{$var}',
	'xml' => '{contentType xml}<b>{$var}</b>',
	'js' => '{contentType javascript}{$var}',
	'parent' => '<title>{block title}My website{/block}</title>',
	'xmlParent' => '{contentType xml}<title>{block title}My website{/block}</title>',
	'child' => '{extends "parent"}{block title}Homepage{/block}',
	'xmlChild' => '{extends "xmlParent"}{block title}Homepage{/block}',
	'blocks' => <<<'X'
		{define html}<i>{$var}</i>{/define}
		<script>{block js}{$var}{/block}</script>
		<div title="{block attr}{$var}{/block}"></div>
		X,
]));


// content type of the template
$html = $latte->renderToContent('html', ['var' => '<>']);
Assert::type(Html::class, $html);
Assert::same('<b>&lt;&gt;</b>', (string) $html);

Assert::type(Html::class, $latte->renderToContent('mimeHtml', ['var' => 1]));

$text = $latte->renderToContent('text', ['var' => '<>']);
Assert::same(Content::class, $text::class);
Assert::same(ContentType::Text, $text->contentType);
Assert::same('<>', (string) $text);

Assert::same(ContentType::Xml, $latte->renderToContent('xml', ['var' => 1])->contentType);

$js = $latte->renderToContent('js', ['var' => '<>']);
Assert::same(ContentType::JavaScript, $js->contentType);
Assert::same('"<>"', (string) $js);


// expected content type
Assert::type(Html::class, $latte->renderToContent('html', ['var' => 1], contentType: ContentType::Html));

$html = $latte->renderToContent('text', ['var' => '<>'], contentType: ContentType::Html);
Assert::type(Html::class, $html);
Assert::same('&lt;&gt;', (string) $html);

Assert::exception(
	fn() => $latte->renderToContent('xml', ['var' => 1], contentType: ContentType::Html),
	Latte\RuntimeException::class,
	"Including 'xml' with content type XML into incompatible type HTML.",
);


// inheritance uses the content type of the layout
Assert::type(Html::class, $latte->renderToContent('child'));
Assert::same('<title>Homepage</title>', (string) $latte->renderToContent('child'));
Assert::same('Homepage', (string) $latte->renderToContent('child', [], 'title'));
Assert::same(ContentType::Xml, $latte->renderToContent('xmlChild')->contentType);

Assert::exception(
	fn() => $latte->renderToContent('xmlChild', contentType: ContentType::Html),
	Latte\RuntimeException::class,
	"Including 'xmlParent' with content type XML into incompatible type HTML.",
);


// single block uses the content type of the block
$html = $latte->renderToContent('blocks', ['var' => '<>'], 'html');
Assert::type(Html::class, $html);
Assert::same('<i>&lt;&gt;</i>', (string) $html);

$js = $latte->renderToContent('blocks', ['var' => '<>'], 'js');
Assert::notSame(ContentType::Html, $js->contentType);
Assert::same('"<>"', (string) $js);

Assert::notSame(ContentType::Html, $latte->renderToContent('blocks', ['var' => '<>'], 'attr')->contentType);

Assert::exception(
	fn() => $latte->renderToContent('blocks', ['var' => '<>'], 'js', ContentType::Html),
	Latte\RuntimeException::class,
	'Including block js with content type %a% into incompatible type HTML.',
);

Assert::exception(
	fn() => $latte->renderToContent('blocks', [], 'unknown'),
	Latte\RuntimeException::class,
	"Cannot include undefined block 'unknown'%a?%",
);
