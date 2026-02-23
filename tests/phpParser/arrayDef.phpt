<?php declare(strict_types=1);

// Array definitions

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	array(),
	array('a'),
	array('a', ),
	array('a', 'b'),
	array('a', &$b, 'c' => 'd', 'e' => &$f),

	/* short array syntax */
	[],
	[1, 2, 3],
	['a' => 'b'],

	/* modern syntax */
	[a: 'b', x: 3],
	[y : 'c'],
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:7
   |  |  |  position: 2:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:7
   |  |  |  position: 3:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:7
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:12
   |  |  |  position: 4:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:7
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:13
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:12
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  position: 5:24
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:17
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 5:37
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'e'
   |  |  |  |  |  |  position: 5:29
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:29
   |  |  |  position: 5:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: 8:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:2
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:5
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:8
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:8
   |  |  |  position: 9:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 10:9
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 10:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:2
   |  |  |  position: 10:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 13:5
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 13:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 13:2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:13
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 13:10
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 13:10
   |  |  |  position: 13:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 14:6
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'y'
   |  |  |  |  |  |  position: 14:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 14:2
   |  |  |  position: 14:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1
   position: 1:1
