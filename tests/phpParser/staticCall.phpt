<?php

// Static calls

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* method name variations */
	A::b(),
	A::{'b'}(),
	A::$b(),
	A::$b['c'](),
	A::$b['c']['d'](),

	/* array dereferencing */
	A::b()['c'],

	/* class name variations */
	static::b(),
	$a::b(),
	${'a'}::b(),
	$a['b']::c(),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:1 (offset 29)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:4 (offset 32)
   |  |  |  args: array (0)
   |  |  |  position: 2:1 (offset 29)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 29)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1 (offset 37)
   |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  position: 3:5 (offset 41)
   |  |  |  args: array (0)
   |  |  |  position: 3:1 (offset 37)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 37)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1 (offset 49)
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 4:4 (offset 52)
   |  |  |  args: array (0)
   |  |  |  position: 4:1 (offset 49)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 49)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 5:1 (offset 58)
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:4 (offset 61)
   |  |  |  |  |  position: 5:1 (offset 58)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  position: 5:7 (offset 64)
   |  |  |  |  position: 5:1 (offset 58)
   |  |  |  args: array (0)
   |  |  |  position: 5:1 (offset 58)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 58)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:1 (offset 72)
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 6:4 (offset 75)
   |  |  |  |  |  |  position: 6:1 (offset 72)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 6:7 (offset 78)
   |  |  |  |  |  position: 6:1 (offset 72)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'd'
   |  |  |  |  |  position: 6:12 (offset 83)
   |  |  |  |  position: 6:1 (offset 72)
   |  |  |  args: array (0)
   |  |  |  position: 6:1 (offset 72)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 72)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 9:1 (offset 118)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 9:4 (offset 121)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 9:1 (offset 118)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 9:8 (offset 125)
   |  |  |  position: 9:1 (offset 118)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 118)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'static'
   |  |  |  |  kind: 1
   |  |  |  |  position: 12:1 (offset 160)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 12:9 (offset 168)
   |  |  |  args: array (0)
   |  |  |  position: 12:1 (offset 160)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 160)
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 13:1 (offset 173)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 13:5 (offset 177)
   |  |  |  args: array (0)
   |  |  |  position: 13:1 (offset 173)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1 (offset 173)
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'a'
   |  |  |  |  |  position: 14:3 (offset 184)
   |  |  |  |  position: 14:1 (offset 182)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 14:9 (offset 190)
   |  |  |  args: array (0)
   |  |  |  position: 14:1 (offset 182)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1 (offset 182)
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 15:1 (offset 195)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 15:4 (offset 198)
   |  |  |  |  position: 15:1 (offset 195)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 15:10 (offset 204)
   |  |  |  args: array (0)
   |  |  |  position: 15:1 (offset 195)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 195)
   position: 2:1 (offset 29)
