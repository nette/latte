<?php

declare(strict_types=1);

use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class OtherNode extends Latte\Compiler\Nodes\AreaNode
{
	public function __construct()
	{
	}


	public function print(Latte\Compiler\PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): Generator
	{
		false && yield;
	}
}


test('constructor/append flattens nested FragmentNode', function () {
	$fragment = new FragmentNode([
		new TextNode('root'),
		new FragmentNode([
			new TextNode('level1'),
			new FragmentNode([
				new TextNode('level2'),
				new FragmentNode([
					new TextNode('level3'),
				]),
			]),
		]),
		new TextNode('end'),
	]);

	$result = exportNode($fragment);

	Assert::match(<<<'XX'
		Latte\Compiler\Nodes\FragmentNode
		   children: array (5)
		   |  0 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'root'
		   |  |  position: null
		   |  1 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'level1'
		   |  |  position: null
		   |  2 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'level2'
		   |  |  position: null
		   |  3 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'level3'
		   |  |  position: null
		   |  4 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'end'
		   |  |  position: null
		   position: null
		XX, $result);
});


test('constructor/append ignores NopNode', function () {
	$fragment = new FragmentNode([
		new TextNode('start'),
		new NopNode,
		new FragmentNode([
			new TextNode('inner'),
			new NopNode,
			new OtherNode,
		]),
		new NopNode,
		new TextNode('end'),
	]);

	$result = exportNode($fragment);

	Assert::match(<<<'XX'
		Latte\Compiler\Nodes\FragmentNode
		   children: array (4)
		   |  0 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'start'
		   |  |  position: null
		   |  1 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'inner'
		   |  |  position: null
		   |  2 => OtherNode
		   |  |  position: null
		   |  3 => Latte\Compiler\Nodes\TextNode
		   |  |  content: 'end'
		   |  |  position: null
		   position: null
		XX, $result);
});
