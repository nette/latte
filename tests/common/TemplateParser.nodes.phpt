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
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   contentType: 'html'
	   position: null
	XX, parse(''));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
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
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (3)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: 'foo '
	   |  |  |  position: 1:1 (offset 0)
	   |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:5 (offset 4)
	   |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: ' bar'
	   |  |  |  position: 2:6 (offset 23)
	   |  position: 1:1 (offset 0)
	   contentType: 'html'
	   position: null
	XX, parse("foo {* comment *}\n{* *} bar"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
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
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  customName: null
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (2)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: 'attr'
	   |  |  |  |  |  |  text: string
	   |  |  |  |  |  |  |  ' \n
	   |  |  |  |  |  |  |   attr=val'
	   |  |  |  |  |  |  value: null
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:4 (offset 3)
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '\n'
	   |  |  |  |  |  |  position: 2:9 (offset 13)
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
	XX, parse("<br \nattr=val\n>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
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
	   |  |  |  |  |  |  position: null
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '...\n'
	   |  |  |  |  |  |  position: 2:1 (offset 4)
	   |  |  |  |  position: 2:1 (offset 4)
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
