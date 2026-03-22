<?php declare(strict_types=1);

// Constant fetches

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	\A,
	A::B,
	A::class,
	$a::B,
	$a::class,
	Foo::{bar()},
	$foo::{bar()},
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 2
   |  |  |  |  position: 1:1+2
   |  |  |  position: 1:1+2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+2
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  position: 2:4+1
   |  |  |  position: 2:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+4
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  position: 3:4+5
   |  |  |  position: 3:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+8
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  position: 4:5+1
   |  |  |  position: 4:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+5
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  position: 5:5+5
   |  |  |  position: 5:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+9
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'Foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 6:1+3
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 6:7+3
   |  |  |  |  args: array (0)
   |  |  |  |  position: 6:7+5
   |  |  |  position: 6:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+12
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 7:1+4
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:8+3
   |  |  |  |  args: array (0)
   |  |  |  |  position: 7:8+5
   |  |  |  position: 7:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+13
   position: 1:1+66
