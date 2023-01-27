<?php

// UVS indirect calls

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	id('var_dump')(1),

	id('id')('var_dump')(2),

	id()()('var_dump')(4),

	id(['udef', 'id'])[1]()('var_dump')(5),

	(function($x) { return $x; })('id')('var_dump')(8),

	($f = function($x = null) use (&$f) {
	    return $x ?: $f;
	})()()()('var_dump')(9),

	[$obj, 'id']()('id')($id)('var_dump')(10),

	'id'()('id')('var_dump')(12),

	('i' . 'd')()('var_dump')(13),

	'\id'('var_dump')(14),
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 1:1 (offset 0)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 1:4 (offset 3)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 1:4 (offset 3)
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:16 (offset 15)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 1:16 (offset 15)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 3:1 (offset 20)
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 3:4 (offset 23)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:4 (offset 23)
   |  |  |  |  |  position: 3:1 (offset 20)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 3:10 (offset 29)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 3:10 (offset 29)
   |  |  |  |  position: 3:1 (offset 20)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 3:22 (offset 41)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:22 (offset 41)
   |  |  |  position: 3:1 (offset 20)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 20)
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 5:1 (offset 46)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 5:1 (offset 46)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 5:1 (offset 46)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 5:8 (offset 53)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 5:8 (offset 53)
   |  |  |  |  position: 5:1 (offset 46)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 4
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:20 (offset 65)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:20 (offset 65)
   |  |  |  position: 5:1 (offset 46)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 46)
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:1 (offset 70)
   |  |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'udef'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5 (offset 74)
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5 (offset 74)
   |  |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13 (offset 82)
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13 (offset 82)
   |  |  |  |  |  |  |  |  |  |  position: 7:4 (offset 73)
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  |  position: 7:4 (offset 73)
   |  |  |  |  |  |  |  position: 7:1 (offset 70)
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 7:20 (offset 89)
   |  |  |  |  |  |  position: 7:1 (offset 70)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 7:1 (offset 70)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 7:25 (offset 94)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 7:25 (offset 94)
   |  |  |  |  position: 7:1 (offset 70)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 5
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 7:37 (offset 106)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 7:37 (offset 106)
   |  |  |  position: 7:1 (offset 70)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 70)
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  position: 9:11 (offset 121)
   |  |  |  |  |  |  |  |  default: null
   |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  position: 9:11 (offset 121)
   |  |  |  |  |  |  uses: array (0)
   |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  position: 9:24 (offset 134)
   |  |  |  |  |  |  position: 9:2 (offset 112)
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 9:31 (offset 141)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 9:31 (offset 141)
   |  |  |  |  |  position: 9:1 (offset 111)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 9:37 (offset 147)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 9:37 (offset 147)
   |  |  |  |  position: 9:1 (offset 111)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:49 (offset 159)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 9:49 (offset 159)
   |  |  |  position: 9:1 (offset 111)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 111)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  position: 11:2 (offset 165)
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:16 (offset 179)
   |  |  |  |  |  |  |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:21 (offset 184)
   |  |  |  |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  |  |  |  position: 11:16 (offset 179)
   |  |  |  |  |  |  |  |  |  uses: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:33 (offset 196)
   |  |  |  |  |  |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  |  |  |  |  |  position: 11:32 (offset 195)
   |  |  |  |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  |  |  |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:12 (offset 213)
   |  |  |  |  |  |  |  |  |  |  if: null
   |  |  |  |  |  |  |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:18 (offset 219)
   |  |  |  |  |  |  |  |  |  |  position: 12:12 (offset 213)
   |  |  |  |  |  |  |  |  |  position: 11:7 (offset 170)
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  position: 11:2 (offset 165)
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 11:1 (offset 164)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 11:1 (offset 164)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 11:1 (offset 164)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 13:10 (offset 232)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 13:10 (offset 232)
   |  |  |  |  position: 11:1 (offset 164)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:22 (offset 244)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 13:22 (offset 244)
   |  |  |  position: 11:1 (offset 164)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 164)
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'obj'
   |  |  |  |  |  |  |  |  |  |  |  position: 15:2 (offset 250)
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:2 (offset 250)
   |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  position: 15:8 (offset 256)
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:8 (offset 256)
   |  |  |  |  |  |  |  |  position: 15:1 (offset 249)
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 15:1 (offset 249)
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  position: 15:16 (offset 264)
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  position: 15:16 (offset 264)
   |  |  |  |  |  |  position: 15:1 (offset 249)
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  position: 15:22 (offset 270)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 15:22 (offset 270)
   |  |  |  |  |  position: 15:1 (offset 249)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 15:27 (offset 275)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 15:27 (offset 275)
   |  |  |  |  position: 15:1 (offset 249)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 15:39 (offset 287)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 15:39 (offset 287)
   |  |  |  position: 15:1 (offset 249)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 249)
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  position: 17:1 (offset 293)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 17:1 (offset 293)
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 17:8 (offset 300)
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 17:8 (offset 300)
   |  |  |  |  |  position: 17:1 (offset 293)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 17:14 (offset 306)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 17:14 (offset 306)
   |  |  |  |  position: 17:1 (offset 293)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 12
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 17:26 (offset 318)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 17:26 (offset 318)
   |  |  |  position: 17:1 (offset 293)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1 (offset 293)
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'i'
   |  |  |  |  |  |  |  position: 19:2 (offset 325)
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  position: 19:8 (offset 331)
   |  |  |  |  |  |  position: 19:2 (offset 325)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 19:1 (offset 324)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 19:15 (offset 338)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 19:15 (offset 338)
   |  |  |  |  position: 19:1 (offset 324)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 13
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 19:27 (offset 350)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 19:27 (offset 350)
   |  |  |  position: 19:1 (offset 324)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1 (offset 324)
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: '\id'
   |  |  |  |  |  position: 21:1 (offset 356)
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 21:7 (offset 362)
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 21:7 (offset 362)
   |  |  |  |  position: 21:1 (offset 356)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 14
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 21:19 (offset 374)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 21:19 (offset 374)
   |  |  |  position: 21:1 (offset 356)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 21:1 (offset 356)
   position: 1:1 (offset 0)
