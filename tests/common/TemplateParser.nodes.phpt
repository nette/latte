<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class FooNode extends Latte\Compiler\Nodes\AreaNode
{
	public function print(Latte\Compiler\PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): Generator
	{
		false && yield;
	}
}


function parse($s)
{
	$parser = new Latte\Compiler\TemplateParser;
	$parser->addTags(['foo' => function () {
		$node = new FooNode;
		yield;
		return $node;
	}]);

	$node = $parser->parse($s);
	return exportNode($node);
}


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   contentType: 'html'
	   position: null
	XX, parse(''));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: string
	   |  |  |  |  '\n
	   |  |  |  |   text\n'
	   |  |  |  position: 1:1+6
	   |  position: 1:1+6
	   contentType: 'html'
	   position: null
	XX, parse("\ntext\n"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (3)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: 'foo '
	   |  |  |  position: 1:1+4
	   |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:18+1
	   |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: ' bar'
	   |  |  |  position: 2:6+4
	   |  position: 1:1+4
	   contentType: 'html'
	   position: null
	XX, parse("foo {* comment *}\n{* *} bar"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (2)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:1+1
	   |  |  1 => FooNode
	   |  |  |  position: 2:1+19
	   |  position: 1:1+1
	   contentType: 'html'
	   position: null
	XX, parse("\n{foo\n} ... \n {/foo}"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (6)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4+1
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr1'
	   |  |  |  |  |  |  |  position: 1:5+5
	   |  |  |  |  |  |  value: null
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:5+5
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' \n'
	   |  |  |  |  |  |  position: 1:10+2
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr2'
	   |  |  |  |  |  |  |  position: 2:1+5
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  position: 2:7+3
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 2:1+9
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: string
	   |  |  |  |  |  |  |  '\n
	   |  |  |  |  |  |  |    '
	   |  |  |  |  |  |  position: 2:10+2
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr3'
	   |  |  |  |  |  |  |  position: 3:2+5
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  position: 4:2+3
	   |  |  |  |  |  |  quote: '''
	   |  |  |  |  |  |  position: 3:2+12
	   |  |  |  |  position: 1:4+1
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1+35
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1+35
	   contentType: 'html'
	   position: null

	XX, parse("<br attr1 \nattr2=val\n attr3=\n'val'>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (6)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4+1
	   |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  position: 1:5+22
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:27+1
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr5'
	   |  |  |  |  |  |  |  position: 1:28+5
	   |  |  |  |  |  |  value: FooNode
	   |  |  |  |  |  |  |  position: 1:34+12
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:28+18
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:46+1
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr6'
	   |  |  |  |  |  |  |  position: 1:47+5
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (3)
	   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'c'
	   |  |  |  |  |  |  |  |  |  position: 1:53+1
	   |  |  |  |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:54+6
	   |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'd'
	   |  |  |  |  |  |  |  |  |  position: 1:60+1
	   |  |  |  |  |  |  |  position: 1:53+1
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:47+14
	   |  |  |  |  position: 1:4+1
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1+61
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1+61
	   contentType: 'html'
	   position: null

	XX, parse("<br {foo}attr4='val'{/foo} attr5={foo}b{/foo} attr6=c{foo/}d>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => FooNode
	   |  |  |  position: 1:5+5
	   |  position: 1:5+5
	   contentType: 'html'
	   position: null
	XX, parse('<br n:foo>'));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (0)
	   |  |  |  |  position: null
	   |  |  |  selfClosing: false
	   |  |  |  content: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (2)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '\n'
	   |  |  |  |  |  |  position: 1:4+1
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '...\n'
	   |  |  |  |  |  |  position: 2:1+4
	   |  |  |  |  position: 1:4+1
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'p'
	   |  |  |  position: 1:1+12
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1+3
	   contentType: 'html'
	   position: null

	XX, parse("<p>\n...\n</p>"));
