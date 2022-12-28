<?php

// Filters

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a|upper),
	($a . $b |upper|truncate),
	($a |truncate: 10, 20|trim),
	($a |truncate: 10, (20|round)|trim),
	($a |truncate: a: 10, b: true),
	($a |truncate( a: 10, b: true)),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:2 (offset 1)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  position: 1:5 (offset 4)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 1:2 (offset 1)
   |  |  |  position: 1:2 (offset 1)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2 (offset 13)
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7 (offset 18)
   |  |  |  |  |  position: 2:2 (offset 13)
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 2:11 (offset 22)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 2:2 (offset 13)
   |  |  |  |  position: 2:2 (offset 13)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 2:17 (offset 28)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 2:2 (offset 13)
   |  |  |  position: 2:2 (offset 13)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 12)
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:2 (offset 40)
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 3:6 (offset 44)
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 3:16 (offset 54)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:16 (offset 54)
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 3:20 (offset 58)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:20 (offset 58)
   |  |  |  |  |  position: 3:2 (offset 40)
   |  |  |  |  position: 3:2 (offset 40)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 3:23 (offset 61)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 3:2 (offset 40)
   |  |  |  position: 3:2 (offset 40)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 39)
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 4:2 (offset 69)
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 4:6 (offset 73)
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 4:16 (offset 83)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 4:16 (offset 83)
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:21 (offset 88)
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 4:24 (offset 91)
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  position: 4:21 (offset 88)
   |  |  |  |  |  |  |  |  position: 4:21 (offset 88)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 4:20 (offset 87)
   |  |  |  |  |  position: 4:2 (offset 69)
   |  |  |  |  position: 4:2 (offset 69)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 4:31 (offset 98)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 4:2 (offset 69)
   |  |  |  position: 4:2 (offset 69)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 68)
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:2 (offset 106)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 5:6 (offset 110)
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 5:19 (offset 123)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 5:16 (offset 120)
   |  |  |  |  |  |  position: 5:16 (offset 120)
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  |  |  |  |  value: true
   |  |  |  |  |  |  |  position: 5:26 (offset 130)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 5:23 (offset 127)
   |  |  |  |  |  |  position: 5:23 (offset 127)
   |  |  |  |  position: 5:2 (offset 106)
   |  |  |  position: 5:2 (offset 106)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 105)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:2 (offset 138)
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 6:6 (offset 142)
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:19 (offset 155)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 6:16 (offset 152)
   |  |  |  |  |  |  position: 6:16 (offset 152)
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  |  |  |  |  value: true
   |  |  |  |  |  |  |  position: 6:26 (offset 162)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 6:23 (offset 159)
   |  |  |  |  |  |  position: 6:23 (offset 159)
   |  |  |  |  position: 6:2 (offset 138)
   |  |  |  position: 6:2 (offset 138)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 137)
   position: 1:1 (offset 0)
