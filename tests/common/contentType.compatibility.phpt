<?php

/**
 * Test: block types compatibility
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('block type compatibility in HTML contexts', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	Assert::same(
		'<meta content="b&quot;ar&quot;&lt;&gt;&amp;">b"ar"<>&amp;',
		$latte->renderToString('<meta content="{include foo}">{block foo}{$value}"<>&amp;{/block}', ['value' => 'b"ar']),
	);

	Assert::same(
		'<meta content="b&quot;ar">b"ar',
		$latte->renderToString('<meta content={include foo}>{block foo}{$value}{/block}', ['value' => 'b"ar']),
	);

	Assert::same(
		'<meta content="b"ar">b"ar',
		$latte->renderToString('<meta content={include foo|noescape}>{block foo}{$value}{/block}', ['value' => 'b"ar']),
	);

	Assert::same(
		'<meta content="b&quot;ar&quot;"><meta content="b&quot;ar&quot;">',
		$latte->renderToString('<meta content="{block foo}{$value}&quot;{/block}"><meta content="{include foo}">', ['value' => 'b"ar']),
	);

	Assert::same(
		'<a href="b&quot;ar"><meta content="b&quot;ar">',
		$latte->renderToString('<a href="{block foo}{$value}{/block}"><meta content="{include foo}">', ['value' => 'b"ar']),
	);

	Assert::same(
		'<meta content="b&quot;ar"><meta content="b&quot;ar">',
		$latte->renderToString('<meta content={block foo}{$value}{/block}><meta content={include foo}>', ['value' => 'b"ar']),
	);

	Assert::same(
		'foo<div>foo</div>',
		$latte->renderToString('{block main}foo{/block}<div>{include main}</div>'),
	);

	Assert::same(
		'<div>foo</div><div>foo</div>',
		$latte->renderToString('{include main}{block main}<div>foo</div>{/block}'),
	);

	Assert::same(
		'a"B<p title="a&quot;B"></p>',
		$latte->renderToString('{block main}a"B{/block}{var $name = main}<p title="{block $name}xx{/block}"></p>'),
	);

	Assert::same(
		'<!-- - - - -->---',
		$latte->renderToString('<!--{include main}-->{block main}---{/block}'),
	);
});


test('template inheritance with content type checks', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'parent' => '<meta name={block foo}{/block}>',

		'parentvar' => '{var $name = "foo"}<meta name={block $name}{/block}>',

		'context1' => <<<'XX'

			{extends "parent"}
			<meta name={block foo}{$foo}{/block}>

			XX,

		'context2' => <<<'XX'

			{extends "parentvar"}
			<meta name={block foo}{$foo}{/block}>

			XX,

		'context3' => <<<'XX'

			{extends "parent"}
			{block foo}{$foo}{/block}

			XX,

		'context4' => <<<'XX'

			{extends "parentvar"}
			{block foo}{$foo}{/block}

			XX,

		'context5' => <<<'XX'

			{extends "parent"}
			<meta name="{block foo}{$foo}{/block}">

			XX,

		'context6' => <<<'XX'

			{extends "parentvar"}
			<meta name="{block foo}{$foo}{/block}">

			XX,

		'parentattr' => '<meta name="{block foo}{$foo}{/block}">',

		'context7' => <<<'XX'

			{extends "parentattr"}
			{block foo}{$foo} {include parent} "<>&amp;{/block}

			XX,

		'context8' => <<<'XX'

			{extends "parent"}
			<!--{block foo}{$foo}{/block}-->

			XX,
	]));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context1', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context2', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context3', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context4', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context5', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString('context6', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar b&quot;ar &quot;&lt;&gt;&amp;">', $latte->renderToString('context7', ['foo' => 'b"ar']));

	Assert::exception(
		fn() => $latte->renderToString('context8', ['foo' => 'b"ar']),
		Latte\RuntimeException::class,
		'Overridden block foo with content type HTML/ATTR by incompatible type HTML/COMMENT.',
	);
});


test('block inclusion in different content types', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	Assert::match(
		'<div><h1>title</h1></div> <h1>title</h1>',
		$latte->renderToString('<div>{include title}</div> <h1 n:block=title>title</h1>'),
	);

	Assert::match(
		'<div><style>...</style></div> <style>...</style>',
		$latte->renderToString('<div>{include style}</div> <style n:block=style>...</style>'),
	);

	Assert::exception(
		fn() => $latte->renderToString('<div>{include style}</div> <STYLE n:inner-block=style>...</STYLE>'),
		Latte\RuntimeException::class,
		'Including block style with content type HTML/RAW/CSS into incompatible type HTML.',
	);

	Assert::match(
		'<div><script>...</script></div> <script>...</script>',
		$latte->renderToString('<div>{include script}</div> <script n:block=script>...</script>'),
	);

	Assert::exception(
		fn() => $latte->renderToString('<div>{include script}</div> <script n:inner-block=script>...</script>'),
		Latte\RuntimeException::class,
		'Including block script with content type HTML/RAW/JS into incompatible type HTML.',
	);
});


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'ical.latte' => '{contentType text/calendar; charset=utf-8} <>',

	'context1' => '<p>{include ical.latte}</p>',
	'context2' => '{extends ical.latte}',
	'context3' => 'x{include ical.latte with blocks}',
	'context4' => '{contentType calendar} {include ical.latte}',
	'context5' => '<p>{include ical.latte|noescape}</p>',
	'context6' => '{contentType javascript} {include ical.latte}',
	'context7' => '<!--{include ical.latte}-->',
]));

Assert::exception(
	fn() => $latte->renderToString('context1'),
	Latte\RuntimeException::class,
	"Including 'ical.latte' with content type ICAL into incompatible type HTML.",
);

Assert::same(' <>', $latte->renderToString('context2'));

Assert::exception(
	fn() => $latte->renderToString('context3'),
	Latte\RuntimeException::class,
	"Including 'ical.latte' with content type ICAL into incompatible type HTML.",
);

Assert::same('  <>', $latte->renderToString('context4'));

Assert::same('<p> <></p>', $latte->renderToString('context5'));

Assert::exception(
	fn() => $latte->renderToString('context6'),
	Latte\RuntimeException::class,
	"Including 'ical.latte' with content type ICAL into incompatible type JS.",
);

Assert::exception(
	fn() => $latte->renderToString('context7'),
	Latte\RuntimeException::class,
	"Including 'ical.latte' with content type ICAL into incompatible type HTML/COMMENT.",
);



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'js.latte' => '{contentType javascript} </script>',

	'context1' => '<p>{include js.latte}</p>',
	'context2' => '<p title="{include js.latte}"></p>',
	'context3' => '<p title={include js.latte}></p>',
	'context4' => '<script>{include js.latte}</script>',
	'context5' => '<style>{include js.latte}</style>',
	'context6' => '<!--{include js.latte}-->',
]));

Assert::same('<p> &lt;/script&gt;</p>', $latte->renderToString('context1'));

Assert::same('<p title=" &lt;/script&gt;"></p>', $latte->renderToString('context2'));

Assert::same('<p title=" &lt;/script&gt;"></p>', $latte->renderToString('context3'));

Assert::same('<script> <\/script></script>', $latte->renderToString('context4'));

Assert::exception(
	fn() => $latte->renderToString('context5'),
	Latte\RuntimeException::class,
	"Including 'js.latte' with content type JS into incompatible type HTML/RAW/CSS.",
);

Assert::same('<!-- </script>-->', $latte->renderToString('context6'));



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'html.latte' => '<hr><script></script> " &quot; &lt;',

	'context1' => '<p>{include html.latte}</p>',
	'context1a' => '<p>{include html.latte|noescape}</p>',
	'context1b' => '<p>{include html.latte|stripHtml|upper}</p>',
	'context1c' => '<p>{include html.latte|stripHtml|upper|noescape}</p>',
	'context2' => '<p title="{include html.latte}"></p>',
	'context2a' => '<p title="{include html.latte|noescape}"></p>',
	'context2b' => '<p title="{include html.latte|stripHtml|upper}"></p>',
	'context2c' => '<p title="{include html.latte|stripHtml|upper|noescape}"></p>',
	'context3' => '<p title={include html.latte}></p>',
	'context4' => '<script>{include html.latte}</script>',
	'context4a' => '<script type="text/html">{include html.latte}</script>',
	'context5' => '<style>{include html.latte}</style>',
	'context6' => '<!--{include html.latte}-->',
]));

Assert::same('<p><hr><script></script> " &quot; &lt;</p>', $latte->renderToString('context1'));

Assert::same('<p><hr><script></script> " &quot; &lt;</p>', $latte->renderToString('context1a'));
Assert::same('<p> " " &lt;</p>', $latte->renderToString('context1b'));
Assert::same('<p> " " <</p>', $latte->renderToString('context1c'));

Assert::same('<p title="&lt;hr&gt;&lt;script&gt;&lt;/script&gt; &quot; &quot; &lt;"></p>', $latte->renderToString('context2'));

Assert::same('<p title="<hr><script></script> " &quot; &lt;"></p>', $latte->renderToString('context2a'));
Assert::same('<p title=" &quot; &quot; &lt;"></p>', $latte->renderToString('context2b'));
Assert::same('<p title=" " " <"></p>', $latte->renderToString('context2c'));

Assert::same('<p title="&lt;hr&gt;&lt;script&gt;&lt;/script&gt; &quot; &quot; &lt;"></p>', $latte->renderToString('context3'));

Assert::exception(
	fn() => $latte->renderToString('context4'),
	Latte\RuntimeException::class,
	"Including 'html.latte' with content type HTML into incompatible type HTML/RAW/JS.",
);

Assert::same('<script type="text/html"><hr><x-script></x-script> " &quot; &lt;</script>', $latte->renderToString('context4a'));

Assert::exception(
	fn() => $latte->renderToString('context5'),
	Latte\RuntimeException::class,
	"Including 'html.latte' with content type HTML into incompatible type HTML/RAW/CSS.",
);

Assert::same('<!--<hr><script></script> " &quot; &lt;-->', $latte->renderToString('context6'));



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'context1' => '<p>{block html}<hr> " &lt;{/block}</p>',
	'context1a' => '<p>{block html|noescape}<hr> " &lt;{/block}</p>',
	'context1b' => '<p>{block html|upper}<hr> " &lt;{/block}</p>',
	'context1c' => '<p>{block html|stripHtml|upper}<hr> " &lt;{/block}</p>',
	'context2' => '<p title="{block html}<hr> &quot;{/block}"></p>',
	'context2a' => '<p title="{block html|stripHtml|upper}<hr> &quot;{/block}"></p>',
	'context6' => '<!--{block html}<hr> &lt;{/block}-->',
	'context6a' => '<!--{block html|stripHtml|upper}<hr> &lt;{/block}-->',
]));

Assert::same('<p><hr> " &lt;</p>', $latte->renderToString('context1'));

Assert::exception(
	fn() => $latte->renderToString('context1a'),
	Latte\CompileException::class,
	'Filter |noescape is not expected here (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->renderToString('context1b'),
	Latte\RuntimeException::class,
	'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.',
);

Assert::same('<p> " &lt;</p>', $latte->renderToString('context1c'));
Assert::same('<p title="<hr> &quot;"></p>', $latte->renderToString('context2'));
Assert::same('<p title=" &quot;"></p>', $latte->renderToString('context2a'));
Assert::same('<!--<hr> &lt;-->', $latte->renderToString('context6'));

Assert::exception(
	fn() => $latte->renderToString('context6a'),
	Latte\RuntimeException::class,
	'Filter |stripHtml used with incompatible type HTML/COMMENT.',
);



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'context1' => '<p>{var $n=html}{block $n}<hr> " &lt;{/block}</p>',
	'context1a' => '<p>{var $n=html}{block $n|noescape}<hr> " &lt;{/block}</p>',
	'context1b' => '<p>{var $n=html}{block $n|upper}<hr> " &lt;{/block}</p>',
	'context1c' => '<p>{var $n=html}{block $n|stripHtml|upper}<hr> " &lt;{/block}</p>',
	'context2' => '<p title="{var $n=html}{block $n}<hr> &quot;{/block}"></p>',
	'context2a' => '<p title="{var $n=html}{block $n|stripHtml|upper}<hr> &quot;{/block}"></p>',
	'context6' => '<!--{var $n=html}{block $n}<hr> &lt;{/block}-->',
	'context6a' => '<!--{var $n=html}{block $n|stripHtml|upper}<hr> &lt;{/block}-->',
]));

Assert::same('<p><hr> " &lt;</p>', $latte->renderToString('context1'));

Assert::exception(
	fn() => $latte->renderToString('context1a'),
	Latte\CompileException::class,
	'Filter |noescape is not expected here (on line 1 at column 17)',
);

Assert::exception(
	fn() => $latte->renderToString('context1b'),
	Latte\RuntimeException::class,
	'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.',
);

Assert::same('<p> " &lt;</p>', $latte->renderToString('context1c'));
Assert::same('<p title="<hr> &quot;"></p>', $latte->renderToString('context2'));
Assert::same('<p title=" &quot;"></p>', $latte->renderToString('context2a'));
Assert::same('<!--<hr> &lt;-->', $latte->renderToString('context6'));

Assert::exception(
	fn() => $latte->renderToString('context6a'),
	Latte\RuntimeException::class,
	'Filter |stripHtml used with incompatible type HTML/COMMENT.',
);



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'context1' => '<p>{block}<hr> " &lt;{/block}</p>',
	'context1a' => '<p>{block|noescape}<hr> " &lt;{/block}</p>',
	'context1b' => '<p>{block|upper}<hr> " &lt;{/block}</p>',
	'context1c' => '<p>{block|stripHtml|upper}<hr> " &lt;{/block}</p>',
	'context2' => '<p title="{block}<hr> &quot;{/block}"></p>',
	'context2a' => '<p title="{block|stripHtml|upper}<hr> &quot;{/block}"></p>',
	'context6' => '<!--{block}<hr> &lt;{/block}-->',
	'context6a' => '<!--{block|stripHtml|upper}<hr> &lt;{/block}-->',
]));

Assert::same('<p><hr> " &lt;</p>', $latte->renderToString('context1'));

Assert::exception(
	fn() => $latte->renderToString('context1a'),
	Latte\CompileException::class,
	'Filter |noescape is not expected here (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->renderToString('context1b'),
	Latte\RuntimeException::class,
	'Filter |upper is called with incompatible content type HTML, try to prepend |stripHtml.',
);

Assert::same('<p> " &lt;</p>', $latte->renderToString('context1c'));
Assert::same('<p title="<hr> &quot;"></p>', $latte->renderToString('context2'));
Assert::same('<p title=" &quot;"></p>', $latte->renderToString('context2a'));
Assert::same('<!--<hr> &lt;-->', $latte->renderToString('context6'));

Assert::exception(
	fn() => $latte->renderToString('context6a'),
	Latte\RuntimeException::class,
	'Filter |stripHtml used with incompatible type HTML/COMMENT.',
);
