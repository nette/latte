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
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse(''));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: string
	   |  |  |  |  '\n
	   |  |  |  |   text\n'
	   |  |  |  position: 1:1
	   |  |  |  end: 3:1
	   |  position: 1:1
	   |  end: 3:1
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("\ntext\n"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (3)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: 'foo '
	   |  |  |  position: 1:1
	   |  |  |  end: 1:5
	   |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:18
	   |  |  |  end: 2:1
	   |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: ' bar'
	   |  |  |  position: 2:6
	   |  |  |  end: 2:10
	   |  position: 1:1
	   |  end: 2:10
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("foo {* comment *}\n{* *} bar"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (2)
	   |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  content: '\n'
	   |  |  |  position: 1:1
	   |  |  |  end: 2:1
	   |  |  1 => FooNode
	   |  |  |  position: 2:1
	   |  |  |  end: 4:8
	   |  position: 1:1
	   |  end: 4:8
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("\n{foo\n} ... \n {/foo}"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (6)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4
	   |  |  |  |  |  |  end: 1:5
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr1'
	   |  |  |  |  |  |  |  position: 1:5
	   |  |  |  |  |  |  |  end: 1:10
	   |  |  |  |  |  |  value: null
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:5
	   |  |  |  |  |  |  end: 1:10
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' \n'
	   |  |  |  |  |  |  position: 1:10
	   |  |  |  |  |  |  end: 2:1
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr2'
	   |  |  |  |  |  |  |  position: 2:1
	   |  |  |  |  |  |  |  end: 2:6
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  position: 2:7
	   |  |  |  |  |  |  |  end: 2:10
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 2:1
	   |  |  |  |  |  |  end: 2:10
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: string
	   |  |  |  |  |  |  |  '\n
	   |  |  |  |  |  |  |    '
	   |  |  |  |  |  |  position: 2:10
	   |  |  |  |  |  |  end: 3:2
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr3'
	   |  |  |  |  |  |  |  position: 3:2
	   |  |  |  |  |  |  |  end: 3:7
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'val'
	   |  |  |  |  |  |  |  position: 4:2
	   |  |  |  |  |  |  |  end: 4:5
	   |  |  |  |  |  |  quote: '''
	   |  |  |  |  |  |  position: 3:2
	   |  |  |  |  |  |  end: 4:6
	   |  |  |  |  position: 1:4
	   |  |  |  |  end: 4:6
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1
	   |  |  |  end: 4:7
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1
	   |  end: 4:7
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("<br attr1 \nattr2=val\n attr3=\n'val'>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (6)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:4
	   |  |  |  |  |  |  end: 1:5
	   |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  position: 1:5
	   |  |  |  |  |  |  end: 1:27
	   |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:27
	   |  |  |  |  |  |  end: 1:28
	   |  |  |  |  |  3 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr5'
	   |  |  |  |  |  |  |  position: 1:28
	   |  |  |  |  |  |  |  end: 1:33
	   |  |  |  |  |  |  value: FooNode
	   |  |  |  |  |  |  |  position: 1:34
	   |  |  |  |  |  |  |  end: 1:46
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:28
	   |  |  |  |  |  |  end: 1:46
	   |  |  |  |  |  4 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: ' '
	   |  |  |  |  |  |  position: 1:46
	   |  |  |  |  |  |  end: 1:47
	   |  |  |  |  |  5 => Latte\Compiler\Nodes\Html\AttributeNode
	   |  |  |  |  |  |  name: Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  content: 'attr6'
	   |  |  |  |  |  |  |  position: 1:47
	   |  |  |  |  |  |  |  end: 1:52
	   |  |  |  |  |  |  value: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  |  |  |  children: array (3)
	   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'c'
	   |  |  |  |  |  |  |  |  |  position: 1:53
	   |  |  |  |  |  |  |  |  |  end: 1:54
	   |  |  |  |  |  |  |  |  1 => FooNode
	   |  |  |  |  |  |  |  |  |  position: 1:54
	   |  |  |  |  |  |  |  |  |  end: 1:60
	   |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  |  |  |  content: 'd'
	   |  |  |  |  |  |  |  |  |  position: 1:60
	   |  |  |  |  |  |  |  |  |  end: 1:61
	   |  |  |  |  |  |  |  position: 1:53
	   |  |  |  |  |  |  |  end: 1:61
	   |  |  |  |  |  |  quote: null
	   |  |  |  |  |  |  position: 1:47
	   |  |  |  |  |  |  end: 1:61
	   |  |  |  |  position: 1:4
	   |  |  |  |  end: 1:61
	   |  |  |  selfClosing: false
	   |  |  |  content: null
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'br'
	   |  |  |  position: 1:1
	   |  |  |  end: 1:62
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1
	   |  end: 1:62
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("<br {foo}attr4='val'{/foo} attr5={foo}b{/foo} attr6=c{foo/}d>"));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => FooNode
	   |  |  |  position: 1:5
	   |  |  |  end: 1:10
	   |  position: 1:5
	   |  end: 1:10
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse('<br n:foo>'));


Assert::match(<<<'XX'
	Latte\Compiler\Nodes\TemplateNode
	   head: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (0)
	   |  position: null
	   |  end: null
	   main: Latte\Compiler\Nodes\FragmentNode
	   |  children: array (1)
	   |  |  0 => Latte\Compiler\Nodes\Html\ElementNode
	   |  |  |  attributes: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (0)
	   |  |  |  |  position: null
	   |  |  |  |  end: null
	   |  |  |  selfClosing: false
	   |  |  |  content: Latte\Compiler\Nodes\FragmentNode
	   |  |  |  |  children: array (2)
	   |  |  |  |  |  0 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '\n'
	   |  |  |  |  |  |  position: 1:4
	   |  |  |  |  |  |  end: 2:1
	   |  |  |  |  |  1 => Latte\Compiler\Nodes\TextNode
	   |  |  |  |  |  |  content: '...\n'
	   |  |  |  |  |  |  position: 2:1
	   |  |  |  |  |  |  end: 3:1
	   |  |  |  |  position: 1:4
	   |  |  |  |  end: 3:1
	   |  |  |  nAttributes: array (0)
	   |  |  |  dynamicTag: null
	   |  |  |  breakable: false
	   |  |  |  name: 'p'
	   |  |  |  position: 1:1
	   |  |  |  end: 3:5
	   |  |  |  parent: null
	   |  |  |  contentType: 'html'
	   |  position: 1:1
	   |  end: 3:5
	   contentType: 'html'
	   position: null
	   end: null
	XX, parse("<p>\n...\n</p>"));
