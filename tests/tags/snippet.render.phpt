<?php

/**
 * Test: BlockMacros and snippets
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridgeMock.php';



$dataSets = [

	//simple snippet
	[
		['main' => '{snippet foo}hello{/snippet}'],
		['foo' => 'hello'],
		'<div id="foo">hello</div>',
		['foo'],
	],
	//snippetArea with nested snippet
	[
		['main' => '{snippetArea fooWrapper}{php $this->global->value = hello}{snippet foo}{$this->global->value}{/snippet}{/snippetArea}'],
		['foo' => 'hello'],
		'<div id="foo">hello</div>',
		['fooWrapper', 'foo'],
	],
	//two nested snippets
	[
		['main' => '{snippet foo}{snippet foo2}hello{/snippet}{/snippet}'],
		['foo' => '<div id="foo2">hello</div>'],
		'<div id="foo"><div id="foo2">hello</div></div>',
		['foo'],
	],
	[
		['main' => '{snippet foo}{snippet foo2}hello{/snippet}{/snippet}'],
		['foo2' => 'hello'],
		'<div id="foo"><div id="foo2">hello</div></div>',
		['foo2'],
	],
	[
		['main' => '{snippet foo}{snippet foo2}hello{/snippet}{/snippet}'],
		['foo' => '<div id="foo2">hello</div>'],
		'<div id="foo"><div id="foo2">hello</div></div>',
		['foo', 'foo2'],
	],
	//included template
	[
		[
			'main' => '{snippet foo}{include file "sub"}{/snippet}',
			'sub' => '{snippet subFoo}hello{/snippet}',
		],
		['foo' => '<div id="subFoo">hello</div>'],
		'<div id="foo"><div id="subFoo">hello</div></div>',
		['foo'],
	],
	//included template 2
	[
		[
			'main' => '{snippet foo}{include file "sub"}{/snippet}',
			'sub' => '{snippet subFoo}hello{/snippet}',
		],
		['foo' => '<div id="subFoo">hello</div>'],
		'<div id="foo"><div id="subFoo">hello</div></div>',
		['foo', 'subFoo'],
	],
	//included template - expected empty payload
	[
		[
			'main' => '{include file "sub"}',
			'sub' => '{snippet subFoo}hello{/snippet}',
		],
		[],
		'<div id="subFoo">hello</div>',
		['subFoo'],
	],
	//included template - snippetArea
	[
		[
			'main' => '{snippetArea foo}{include file "sub"}{/snippetArea}',
			'sub' => '{snippet subFoo}hello{/snippet}',
		],
		['subFoo' => 'hello'],
		'<div id="subFoo">hello</div>',
		['foo', 'subFoo'],
	],
	//nested included template - snippetArea
	[
		[
			'main' => '{snippetArea foo}{include file "sub"}{/snippetArea}',
			'sub' => '{include file "sub2"}',
			'sub2' => '{snippet sub2Foo}hello{/snippet}',
		],
		['sub2Foo' => 'hello'],
		'<div id="sub2Foo">hello</div>',
		['foo', 'sub2Foo'],
	],
	//dynamic snippets
	[
		[
			'main' => '
{snippet foo}
	{foreach [1, 2] as $id}
		{snippet "bar-$id"}{$id}{/snippet}
	{/foreach}
{/snippet}
',
		],
		['bar-1' => '1', 'bar-2' => '2'],
		"\n<div id=\"foo\">\t\t<div id=\"bar-1\">1</div>\n\t\t<div id=\"bar-2\">2</div>\n</div>",
		['foo'],
	],
	//dynamic snippets with a snippetArea
	[
		[
			'main' => '
{snippetArea foo}
	{foreach [1, 2] as $id}
		{snippet "bar-$id"}{$id}{/snippet}
	{/foreach}
{/snippetArea}
',
		],
		['bar-1' => '1', 'bar-2' => '2'],
		"\n\t\t<div id=\"bar-1\">1</div>\n\t\t<div id=\"bar-2\">2</div>",
		['foo'],
	],
	//snippetArea with a dynamic snippet and included template
	[
		[
			'main' => '
{snippetArea foo}
	{foreach [1, 2] as $id}
		{snippet "bar-$id"}{include file "sub" id => $id}{/snippet}
	{/foreach}
{/snippetArea}
',
			'sub' => '{$id}',
		],
		['bar-1' => '1', 'bar-2' => '2'],
		"\n\t\t<div id=\"bar-1\">1</div>\n\t\t<div id=\"bar-2\">2</div>",
		['foo'],
	],
	//extends
	[
		[
			'main' => '{extends "layout"}{block content}{snippet foo}hello{/snippet}',
			'layout' => '{include content}{snippet layoutFoo}world{/snippet}',
		],
		['foo' => 'hello', 'layoutFoo' => 'world'],
		'<div id="foo">hello</div><div id="layoutFoo">world</div>',
		['foo', 'layoutFoo'],
	],
	//import
	[
		[
			'main' => '{import "blocks1"}{import "blocks2"}{snippet foo}{include block1}{include block2}{/snippet}',
			'blocks1' => '{block block1}hello{/block}',
			'blocks2' => '{block block2} world{/block}',
		],
		['foo' => 'hello world'],
		'<div id="foo">hello world</div>',
		['foo'],
	],
	//import and extends
	[
		[
			'main' => '{extends "layout"}{import "blocks1"}{import "blocks2"}{block content}{snippet foo}{include block1}{include block2}{/snippet}',
			'layout' => '{include content}',
			'blocks1' => '{block block1}hello{/block}',
			'blocks2' => '{block block2} world{/block}',
		],
		['foo' => 'hello world'],
		'<div id="foo">hello world</div>',
		['foo'],
	],
	//embed
	[
		[
			'main' => '{embed file "embed"}{snippet foo}hello{/snippet}{block embed}{snippet bar}world{/snippet}{/block}{/embed}',
			'embed' => '{block embed}{/block}',
		],
		['foo' => 'hello'],
		'<div id="bar">world</div>',
		['foo'],
	],
];

foreach ($dataSets as $data) {
	//snippet mode
	$bridge = new SnippetBridgeMock;
	$bridge->invalid = array_fill_keys($data[3], true);

	$engine = new Latte\Engine;
	$engine->addProvider('snippetBridge', $bridge);
	$engine->setLoader(new Latte\Loaders\StringLoader($data[0]));
	$engine->render('main');

	Assert::same($data[1], $bridge->payload);

	//non snippet mode
	$bridge = new SnippetBridgeMock;
	$bridge->snippetMode = false;
	$bridge->invalid = array_fill_keys($data[3], true);

	$engine = new Latte\Engine;
	$engine->addProvider('snippetBridge', $bridge);
	$engine->setLoader(new Latte\Loaders\StringLoader($data[0]));

	$result = $engine->renderToString('main');

	Assert::match($data[2], $result);
}
