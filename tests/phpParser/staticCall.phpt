<?php declare(strict_types=1);

// Static calls

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
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:4+1
   |  |  |  args: array (0)
   |  |  |  position: 2:1+6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+6
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  position: 3:5+3
   |  |  |  args: array (0)
   |  |  |  position: 3:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 4:4+2
   |  |  |  args: array (0)
   |  |  |  position: 4:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+7
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 5:1+1
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:4+2
   |  |  |  |  |  position: 5:1+5
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  position: 5:7+3
   |  |  |  |  position: 5:1+10
   |  |  |  args: array (0)
   |  |  |  position: 5:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+12
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:1+1
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 6:4+2
   |  |  |  |  |  |  position: 6:1+5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 6:7+3
   |  |  |  |  |  position: 6:1+10
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'd'
   |  |  |  |  |  position: 6:12+3
   |  |  |  |  position: 6:1+15
   |  |  |  args: array (0)
   |  |  |  position: 6:1+17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+17
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 9:1+1
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 9:4+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 9:1+6
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 9:8+3
   |  |  |  position: 9:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+11
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'static'
   |  |  |  |  kind: 1
   |  |  |  |  position: 12:1+6
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 12:9+1
   |  |  |  args: array (0)
   |  |  |  position: 12:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1+11
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 13:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 13:5+1
   |  |  |  args: array (0)
   |  |  |  position: 13:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1+7
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'a'
   |  |  |  |  |  position: 14:3+3
   |  |  |  |  position: 14:1+6
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 14:9+1
   |  |  |  args: array (0)
   |  |  |  position: 14:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1+11
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 15:1+2
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 15:4+3
   |  |  |  |  position: 15:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 15:10+1
   |  |  |  args: array (0)
   |  |  |  position: 15:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+12
   position: 2:1+179
