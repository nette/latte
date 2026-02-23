<?php declare(strict_types=1);

// Named arguments

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo(a: $b, c: $d),
	bar(class: 0),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (2)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 1:1
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 1:8
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:5
   |  |  |  |  |  position: 1:5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 1:15
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 1:12
   |  |  |  |  |  position: 1:12
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'bar'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'class'
   |  |  |  |  |  |  position: 2:5
   |  |  |  |  |  position: 2:5
   |  |  |  position: 2:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   position: 1:1
