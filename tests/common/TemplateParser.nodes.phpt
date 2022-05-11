<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class FooNode extends Latte\Compiler\Nodes\AreaNode
{
	public function print(Latte\Compiler\PrintContext $context): string
	{
		return '';
	}
}


function parse($s)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	$parser = new Latte\Compiler\TemplateParser;
	$parser->addTags(['foo' => function () {
		$node = new FooNode;
		yield;
		return $node;
	}]);

	$node = $parser->parse($s, $lexer);
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
	   |  |  |  position: 1:1 (offset 0)
	   |  position: 1:1 (offset 0)
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
	   |  |  |  position: 1:1 (offset 0)
	   |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:18 (offset 17)
	   |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: ' bar'
	   |  |  |  position: 2:6 (offset 23)
	   |  position: 1:1 (offset 0)
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
	   |  |  |  position: 1:1 (offset 0)
	   |  |  1 => FooNode
	   |  |  |  position: 2:1 (offset 1)
	   |  position: 1:1 (offset 0)
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
	   |  |  |  customName: null
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (6)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr1'
	   |  |  |  |  |  |  |  position: 1:5 (offset 4)
	   |  |  |  |  |  |  value: null
	   |  |  |  |  |  |  position: 1:5 (offset 4)
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' \n'
	   |  |  |  |  |  |  position: 1:10 (offset 9)
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr2'
	   |  |  |  |  |  |  |  position: 2:1 (offset 11)
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  position: 2:7 (offset 17)
	   |  |  |  |  |  |  position: 2:1 (offset 11)
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: string
	   |  |  |  |  |  |  |  '\n
	   |  |  |  |  |  |  |    '
	   |  |  |  |  |  |  position: 2:10 (offset 20)
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr3'
	   |  |  |  |  |  |  |  position: 3:2 (offset 22)
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Html\QuotedValue
	   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  |  children: array (1)
	   |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  |  |  |  position: 4:2 (offset 30)
	   |  |  |  |  |  |  |  |  position: 4:2 (offset 30)
	   |  |  |  |  |  |  |  quote: '''
	   |  |  |  |  |  |  |  position: 4:1 (offset 29)
	   |  |  |  |  |  |  position: 3:2 (offset 22)
	   |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  tagNode: Latte\Compiler\Nodes\AuxiliaryNode
	   |  |  |  |  callable: Closure($context)
	   |  |  |  |  position: null
	   |  |  |  captureTagName: false
	   |  |  |  endTagVar: unset
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1 (offset 0)
	   |  |  |  parent: null
	   |  |  |  data: stdClass
	   |  |  |  |  tag: null
	   |  position: 1:1 (offset 0)
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
	   |  |  |  customName: null
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (7)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  position: 1:5 (offset 4)
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:27 (offset 26)
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (1)
	   |  |  |  |  |  |  |  |  0 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:28 (offset 27)
	   |  |  |  |  |  |  |  position: 1:28 (offset 27)
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (1)
	   |  |  |  |  |  |  |  |  0 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:45 (offset 44)
	   |  |  |  |  |  |  |  position: 1:45 (offset 44)
	   |  |  |  |  |  |  position: 1:28 (offset 27)
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:57 (offset 56)
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (3)
	   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'attr6'
	   |  |  |  |  |  |  |  |  |  position: 1:58 (offset 57)
	   |  |  |  |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:63 (offset 62)
	   |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'b'
	   |  |  |  |  |  |  |  |  |  position: 1:69 (offset 68)
	   |  |  |  |  |  |  |  position: 1:58 (offset 57)
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'c'
	   |  |  |  |  |  |  |  position: 1:71 (offset 70)
	   |  |  |  |  |  |  position: 1:58 (offset 57)
	   |  |  |  |  |  6 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (2)
	   |  |  |  |  |  |  |  |  0 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:72 (offset 71)
	   |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'd'
	   |  |  |  |  |  |  |  |  |  position: 1:78 (offset 77)
	   |  |  |  |  |  |  |  position: 1:72 (offset 71)
	   |  |  |  |  |  |  value: null
	   |  |  |  |  |  |  position: 1:72 (offset 71)
	   |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  tagNode: Latte\Compiler\Nodes\AuxiliaryNode
	   |  |  |  |  callable: Closure($context)
	   |  |  |  |  position: null
	   |  |  |  captureTagName: false
	   |  |  |  endTagVar: unset
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1 (offset 0)
	   |  |  |  parent: null
	   |  |  |  data: stdClass
	   |  |  |  |  tag: null
	   |  position: 1:1 (offset 0)
	   contentType: 'html'
	   position: null
	XX, parse("<br {foo}attr4='val'{/foo} {foo}attr5{/foo}={foo}b{/foo} attr6{foo/}b=c{foo/}d>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => FooNode
	   |  |  |  position: 1:5 (offset 4)
	   |  position: 1:5 (offset 4)
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
	   |  |  |  customName: null
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (0)
	   |  |  |  |  position: null
	   |  |  |  selfClosing: false
	   |  |  |  content: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (2)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '\n'
	   |  |  |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '...\n'
	   |  |  |  |  |  |  position: 2:1 (offset 4)
	   |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  nAttributes: array (0)
	   |  |  |  tagNode: Latte\Compiler\Nodes\AuxiliaryNode
	   |  |  |  |  callable: Closure($context)
	   |  |  |  |  position: null
	   |  |  |  captureTagName: false
	   |  |  |  endTagVar: unset
	   |  |  |  name: 'p'
	   |  |  |  position: 1:1 (offset 0)
	   |  |  |  parent: null
	   |  |  |  data: stdClass
	   |  |  |  |  tag: null
	   |  position: 1:1 (offset 0)
	   contentType: 'html'
	   position: null
	XX, parse("<p>\n...\n</p>"));
