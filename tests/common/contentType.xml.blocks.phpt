<?php declare(strict_types=1);

/**
 * Test: blocks and includes in XML have the XML content type
 */

use Latte\Compiler\Escaper;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'text' => '{contentType text}{$v}',
	'html' => '<p>{$v}</p>',
	'js' => '{contentType javascript}{$v}',
	'xml' => '{contentType xml}<a>{$v}</a>',
	'xmlDefine' => '{contentType xml}{define d}<i>{$v}</i>{/define}',
	'xmlMain' => <<<'X'
		{contentType xml}
		<r a="{include file text}">{include file text}</r>
		<r>{include file xml}</r>
		<r>{include file js}</r>
		<!--{include file text}-->
		X,
	'textMain' => '{contentType text}{include file xml}',
	'htmlIntoXml' => '{contentType xml}<r>{include file html}</r>',
	'htmlIntoXmlAttr' => '{contentType xml}<r a="{include file html}"/>',
	'xmlIntoHtml' => '{import xmlDefine}<p>{include d}</p>',
	'xmlIntoHtmlAttr' => '{import xmlDefine}<p title="{include d}"></p>',
	'xmlIntoScript' => '{import xmlDefine}<script>{include d}</script>',
]));
$inline = new Latte\Engine;
$inline->setLoader(new Latte\Loaders\StringLoader);


test('XML contexts are exported as XML', function () use ($inline) {
	Assert::contains(
		"['attr' => ['blockAttr', 'xml/attr'], 'text' => 'blockText', 'comment' => ['blockComment', 'xml/comment']]",
		$inline->compile('{contentType xml}<a b="{block attr}{/block}">{block text}{/block}</a><!--{block comment}{/block}-->'),
	);

	$escaper = new Escaper(Latte\ContentType::Xml);
	Assert::same('xml', $escaper->export());
	$escaper->enterHtmlAttribute('onclick');
	Assert::same('xml/attr', $escaper->export());
});


test('includes within XML', function () use ($latte) {
	Assert::same(<<<"X"
		<r a="a&lt;&quot;\u{FFFD}">a&lt;&quot;\u{FFFD}</r>
		<r><a>a&lt;&quot;\u{FFFD}</a></r>
		<r>&quot;a&lt;\\&quot;\\u0001&quot;</r>
		<!--a<"\x01-->
		X, $latte->renderToString('xmlMain', ['v' => "a<\"\x01"]));

	Assert::same('a<"', $latte->renderToString('textMain', ['v' => 'a<"']));
});


test('blocks within XML', function () use ($inline) {
	Assert::same(
		'<r><i>a&lt;</i></r><s t="a&lt;"/><!-- <i>a&lt;</i> -->',
		$inline->renderToString(
			'{contentType xml}<r>{block b}<i>{$v}</i>{/block}</r><s t="{include b}"/><!-- {include b} -->',
			['v' => 'a<'],
		),
	);
	Assert::same(
		'<s t="a&lt;"/><r>a&lt;</r>',
		$inline->renderToString('{contentType xml}<s t="{block a}{$v}{/block}"/><r>{include a}</r>', ['v' => 'a<']),
	);
});


test('filters see the XML content type', function () use ($inline) {
	Assert::exception(
		fn() => $inline->renderToString('{contentType xml}<r>{block|upper}<i>a</i>{/block}</r>'),
		Latte\RuntimeException::class,
		'Filter |upper is called with incompatible content type XML, try to prepend |stripHtml.',
	);
	Assert::same(
		'<r>A&lt;</r>',
		$inline->renderToString('{contentType xml}<r>{block|stripHtml|upper}<i>{$v}</i>{/block}</r>', ['v' => 'a<']),
	);
	Assert::same(
		'<r><i>a&lt;</i></r>',
		$inline->renderToString('{contentType xml}<r>{capture $c|trim} <i>{$v}</i> {/capture}{$c}</r>', ['v' => 'a<']),
	);
});


test('XML and HTML into each other are deprecated', function () use ($latte) {
	Assert::error(
		fn() => Assert::same('<r><p>a&lt;</p></r>', $latte->renderToString('htmlIntoXml', ['v' => 'a<'])),
		E_USER_DEPRECATED,
		'Converting content type HTML into XML is deprecated, XML and HTML will be incompatible.',
	);
	Assert::error(
		fn() => Assert::same('<r a="a&lt;"/>', $latte->renderToString('htmlIntoXmlAttr', ['v' => 'a<'])),
		E_USER_DEPRECATED,
		'Converting content type HTML into XML/ATTR is deprecated, XML and HTML will be incompatible.',
	);
	Assert::error(
		fn() => Assert::same('<p><i>a&lt;</i></p>', $latte->renderToString('xmlIntoHtml', ['v' => 'a<'])),
		E_USER_DEPRECATED,
		'Converting content type XML into HTML is deprecated, XML and HTML will be incompatible.',
	);
	Assert::error(
		fn() => Assert::same('<p title="a&lt;"></p>', $latte->renderToString('xmlIntoHtmlAttr', ['v' => 'a<'])),
		E_USER_DEPRECATED,
		'Converting content type XML into HTML/ATTR is deprecated, XML and HTML will be incompatible.',
	);
	Assert::exception(
		fn() => $latte->renderToString('xmlIntoScript', ['v' => 'a<']),
		Latte\RuntimeException::class,
		'Including block d with content type XML into incompatible type HTML/RAW/JS.',
	);
});
