<?php

// Spread array

declare(strict_types=1);

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
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'array'
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:11 (offset 10)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:11 (offset 10)
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:14 (offset 13)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:14 (offset 13)
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:17 (offset 16)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:17 (offset 16)
   |  |  |  |  position: 1:10 (offset 9)
   |  |  |  byRef: false
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  position: 3:5 (offset 25)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 3:2 (offset 22)
   |  |  |  position: 3:1 (offset 21)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 21)
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:6 (offset 35)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:6 (offset 35)
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:9 (offset 38)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:9 (offset 38)
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:12 (offset 41)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:12 (offset 41)
   |  |  |  |  |  |  position: 4:5 (offset 34)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 4:2 (offset 31)
   |  |  |  position: 4:1 (offset 30)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 30)
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 5:5 (offset 50)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 5:2 (offset 47)
   |  |  |  position: 5:1 (offset 46)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 46)
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'getArr'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:5 (offset 63)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 6:5 (offset 63)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 6:2 (offset 60)
   |  |  |  position: 6:1 (offset 59)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 59)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'arrGen'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 7:5 (offset 78)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 7:5 (offset 78)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 7:2 (offset 75)
   |  |  |  position: 7:1 (offset 74)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 74)
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'ArrayIterator'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 8:9 (offset 97)
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:24 (offset 112)
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:24 (offset 112)
   |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:29 (offset 117)
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:29 (offset 117)
   |  |  |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 8:34 (offset 122)
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 8:34 (offset 122)
   |  |  |  |  |  |  |  |  |  position: 8:23 (offset 111)
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  position: 8:23 (offset 111)
   |  |  |  |  |  |  position: 8:5 (offset 93)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 8:2 (offset 90)
   |  |  |  position: 8:1 (offset 89)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 89)
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (9)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:2 (offset 131)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:2 (offset 131)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 9:8 (offset 137)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:5 (offset 134)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'getArr'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 9:19 (offset 148)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 9:19 (offset 148)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:16 (offset 145)
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 6
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:29 (offset 158)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:29 (offset 158)
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 7
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:32 (offset 161)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:32 (offset 161)
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:35 (offset 164)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:35 (offset 164)
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:38 (offset 167)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:38 (offset 167)
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:41 (offset 170)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:41 (offset 170)
   |  |  |  |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'arrGen'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 9:48 (offset 177)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 9:48 (offset 177)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 9:45 (offset 174)
   |  |  |  position: 9:1 (offset 130)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 130)
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 10:2 (offset 189)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:2 (offset 189)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 10:8 (offset 195)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 10:5 (offset 192)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 10:19 (offset 206)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 10:16 (offset 203)
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'end'
   |  |  |  |  |  |  position: 10:27 (offset 214)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:27 (offset 214)
   |  |  |  position: 10:1 (offset 188)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 188)
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:12 (offset 233)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:12 (offset 233)
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:15 (offset 236)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:15 (offset 236)
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 11:18 (offset 239)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:18 (offset 239)
   |  |  |  |  |  |  position: 11:11 (offset 232)
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 11:2 (offset 223)
   |  |  |  position: 11:1 (offset 222)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 222)
   position: 1:1 (offset 0)
