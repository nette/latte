<?php

// Constant fetches

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	A,
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

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (8)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'A'
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 2
   |  |  |  |  position: 2:1 (offset 3)
   |  |  |  position: 2:1 (offset 3)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 3)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1 (offset 7)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  position: 3:4 (offset 10)
   |  |  |  position: 3:1 (offset 7)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 7)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1 (offset 13)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  position: 4:4 (offset 16)
   |  |  |  position: 4:1 (offset 13)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 13)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:1 (offset 23)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  position: 5:5 (offset 27)
   |  |  |  position: 5:1 (offset 23)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 23)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:1 (offset 30)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  position: 6:5 (offset 34)
   |  |  |  position: 6:1 (offset 30)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 30)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 7:1 (offset 41)
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'bar'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:7 (offset 47)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 7:7 (offset 47)
   |  |  |  position: 7:1 (offset 41)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 41)
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 8:1 (offset 55)
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'bar'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 8:8 (offset 62)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 8:8 (offset 62)
   |  |  |  position: 8:1 (offset 55)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 55)
   position: 1:1 (offset 0)
