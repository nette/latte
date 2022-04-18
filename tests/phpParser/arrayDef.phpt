<?php

// Array definitions

declare(strict_types=1);

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
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 2:7 (offset 15)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 2:7 (offset 15)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 9)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 3:7 (offset 27)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 3:7 (offset 27)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 21)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 4:7 (offset 41)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 4:7 (offset 41)
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 4:12 (offset 46)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 4:12 (offset 46)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 35)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 5:7 (offset 58)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 5:7 (offset 58)
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:13 (offset 64)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: true
   |  |  |  |  |  position: 5:12 (offset 63)
   |  |  |  |  |  unpack: false
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  position: 5:24 (offset 75)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 5:17 (offset 68)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 5:17 (offset 68)
   |  |  |  |  |  unpack: false
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 5:37 (offset 88)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'e'
   |  |  |  |  |  |  position: 5:29 (offset 80)
   |  |  |  |  |  byRef: true
   |  |  |  |  |  position: 5:29 (offset 80)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 5:1 (offset 52)
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 8:1 (offset 119)
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:2 (offset 124)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 9:2 (offset 124)
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:5 (offset 127)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 9:5 (offset 127)
   |  |  |  |  |  unpack: false
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:8 (offset 130)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 9:8 (offset 130)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 9:1 (offset 123)
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 10:9 (offset 142)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 10:2 (offset 135)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 10:2 (offset 135)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 10:1 (offset 134)
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 13:5 (offset 173)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 13:2 (offset 170)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 13:2 (offset 170)
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:13 (offset 181)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 13:10 (offset 178)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 13:10 (offset 178)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 13:1 (offset 169)
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 14:6 (offset 190)
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'y'
   |  |  |  |  |  |  position: 14:2 (offset 186)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 14:2 (offset 186)
   |  |  |  |  |  unpack: false
   |  |  |  position: null
   |  |  key: null
   |  |  byRef: false
   |  |  position: 14:1 (offset 185)
   |  |  unpack: false
   position: null
