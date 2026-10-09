<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'html' => '<b>{$var}</b>',
	'explicitHtml' => '{contentType html}<b>{$var}</b>',
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


// HTML template
$html = $latte->renderToHtml('html', ['var' => '<>']);
Assert::type(Latte\Runtime\Html::class, $html);
Assert::same('<b>&lt;&gt;</b>', (string) $html);

Assert::same('<b>&lt;&gt;</b>', (string) $latte->renderToHtml('explicitHtml', ['var' => '<>']));
Assert::same('<b>&lt;&gt;</b>', (string) $latte->renderToHtml('mimeHtml', ['var' => '<>']));


// non-HTML templates
Assert::exception(
	fn() => $latte->renderToHtml('text', ['var' => '<>']),
	Latte\RuntimeException::class,
	"Rendering 'text' with content type TEXT, but HTML was expected.",
);

Assert::exception(
	fn() => $latte->renderToHtml('xml', ['var' => '<>']),
	Latte\RuntimeException::class,
	"Rendering 'xml' with content type XML, but HTML was expected.",
);

Assert::exception(
	fn() => $latte->renderToHtml('js', ['var' => '<>']),
	Latte\RuntimeException::class,
	"Rendering 'js' with content type JS, but HTML was expected.",
);


// inheritance
Assert::same('<title>Homepage</title>', (string) $latte->renderToHtml('child'));
Assert::same('Homepage', (string) $latte->renderToHtml('child', [], 'title'));

Assert::exception(
	fn() => $latte->renderToHtml('xmlChild'),
	Latte\RuntimeException::class,
	"Rendering 'xmlParent' with content type XML, but HTML was expected.",
);


// blocks
Assert::same('<i>&lt;&gt;</i>', (string) $latte->renderToHtml('blocks', ['var' => '<>'], 'html'));

Assert::exception(
	fn() => $latte->renderToHtml('blocks', ['var' => '<>'], 'js'),
	Latte\RuntimeException::class,
	'Rendering block js with content type %a%, but HTML was expected.',
);

Assert::exception(
	fn() => $latte->renderToHtml('blocks', ['var' => '<>'], 'attr'),
	Latte\RuntimeException::class,
	'Rendering block attr with content type %a%, but HTML was expected.',
);

Assert::exception(
	fn() => $latte->renderToHtml('blocks', [], 'unknown'),
	Latte\RuntimeException::class,
	"Cannot include undefined block 'unknown'%a?%",
);


// renderToString is not restricted
Assert::same('<>', $latte->renderToString('text', ['var' => '<>']));
