<?php declare(strict_types=1);

// UVS indirect calls

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

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'id'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 1:1+2
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 1:4+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 1:4+10
   |  |  |  |  position: 1:1+14
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:16+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 1:16+1
   |  |  |  position: 1:1+17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+17
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 3:1+2
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 3:4+4
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:4+4
   |  |  |  |  |  position: 3:1+8
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 3:10+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 3:10+10
   |  |  |  |  position: 3:1+20
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 3:22+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:22+1
   |  |  |  position: 3:1+23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+23
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 5:1+2
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 5:1+4
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 5:1+6
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 5:8+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 5:8+10
   |  |  |  |  position: 5:1+18
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 4
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:20+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:20+1
   |  |  |  position: 5:1+21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+21
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:1+2
   |  |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'udef'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5+6
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5+6
   |  |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13+4
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13+4
   |  |  |  |  |  |  |  |  |  |  position: 7:4+14
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  |  position: 7:4+14
   |  |  |  |  |  |  |  position: 7:1+18
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 7:20+1
   |  |  |  |  |  |  position: 7:1+21
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 7:1+23
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 7:25+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 7:25+10
   |  |  |  |  position: 7:1+35
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 5
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 7:37+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 7:37+1
   |  |  |  position: 7:1+38
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+38
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  position: 9:11+2
   |  |  |  |  |  |  |  |  default: null
   |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  position: 9:11+2
   |  |  |  |  |  |  uses: array (0)
   |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  position: 9:24+2
   |  |  |  |  |  |  position: 9:2+27
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 9:31+4
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 9:31+4
   |  |  |  |  |  position: 9:1+35
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 9:37+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 9:37+10
   |  |  |  |  position: 9:1+47
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:49+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 9:49+1
   |  |  |  position: 9:1+50
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+50
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  position: 11:2+2
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:16+2
   |  |  |  |  |  |  |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:21+4
   |  |  |  |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  |  |  |  position: 11:16+9
   |  |  |  |  |  |  |  |  |  uses: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:33+2
   |  |  |  |  |  |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  |  |  |  |  |  position: 11:32+3
   |  |  |  |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  |  |  |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:12+2
   |  |  |  |  |  |  |  |  |  |  if: null
   |  |  |  |  |  |  |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:18+2
   |  |  |  |  |  |  |  |  |  |  position: 12:12+8
   |  |  |  |  |  |  |  |  |  position: 11:7+54
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  position: 11:2+59
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 11:1+63
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 11:1+65
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 11:1+67
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 13:10+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 13:10+10
   |  |  |  |  position: 11:1+79
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:22+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 13:22+1
   |  |  |  position: 11:1+82
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+82
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'obj'
   |  |  |  |  |  |  |  |  |  |  |  position: 15:2+4
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:2+4
   |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  position: 15:8+4
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:8+4
   |  |  |  |  |  |  |  |  position: 15:1+12
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 15:1+14
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  position: 15:16+4
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  position: 15:16+4
   |  |  |  |  |  |  position: 15:1+20
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  position: 15:22+3
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 15:22+3
   |  |  |  |  |  position: 15:1+25
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 15:27+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 15:27+10
   |  |  |  |  position: 15:1+37
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 15:39+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 15:39+2
   |  |  |  position: 15:1+41
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+41
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  position: 17:1+4
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 17:1+6
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 17:8+4
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 17:8+4
   |  |  |  |  |  position: 17:1+12
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 17:14+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 17:14+10
   |  |  |  |  position: 17:1+24
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 12
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 17:26+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 17:26+2
   |  |  |  position: 17:1+28
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1+28
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'i'
   |  |  |  |  |  |  |  position: 19:2+3
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  position: 19:8+3
   |  |  |  |  |  |  position: 19:2+9
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 19:1+13
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 19:15+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 19:15+10
   |  |  |  |  position: 19:1+25
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 13
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 19:27+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 19:27+2
   |  |  |  position: 19:1+29
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1+29
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: '\id'
   |  |  |  |  |  position: 21:1+5
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 21:7+10
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 21:7+10
   |  |  |  |  position: 21:1+17
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 14
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 21:19+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 21:19+2
   |  |  |  position: 21:1+21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 21:1+21
   position: 1:1+378
