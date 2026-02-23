<?php declare(strict_types=1);

// Spread array

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$array = [1, 2, 3],

	[...[]],
	[...[1, 2, 3]],
	[...$array],
	[...getArr()],
	[...arrGen()],
	[...new ArrayIterator(['a', 'b', 'c'])],
	[0, ...$array, ...getArr(), 6, 7, 8, 9, 10, ...arrGen()],
	[0, ...$array, ...$array, 'end'],
	[(expand) [1, 2, 3]],
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'array'
   |  |  |  |  position: 1:1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:11
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:11
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:14
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:14
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:17
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:17
   |  |  |  |  position: 1:10
   |  |  |  byRef: false
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 3:2
   |  |  |  position: 3:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:6
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:6
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:9
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:9
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  |  position: 4:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 4:2
   |  |  |  position: 4:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 5:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 5:2
   |  |  |  position: 5:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'getArr'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 6:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 6:2
   |  |  |  position: 6:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'arrGen'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 7:2
   |  |  |  position: 7:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'ArrayIterator'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 8:9
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:24
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:24
   |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:29
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:29
   |  |  |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:34
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:34
   |  |  |  |  |  |  |  |  |  position: 8:23
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  position: 8:23
   |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 8:2
   |  |  |  position: 8:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (9)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:2
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 9:8
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:5
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'getArr'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 9:19
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 9:19
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:16
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 6
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:29
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:29
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 7
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:32
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:32
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:35
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:35
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:38
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:38
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:41
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:41
   |  |  |  |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'arrGen'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 9:48
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 9:48
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:45
   |  |  |  position: 9:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 10:2
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 10:8
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 10:5
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 10:19
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 10:16
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'end'
   |  |  |  |  |  |  position: 10:27
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:27
   |  |  |  position: 10:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:12
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:12
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:15
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:15
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:18
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:18
   |  |  |  |  |  |  position: 11:11
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 11:2
   |  |  |  position: 11:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   position: 1:1
