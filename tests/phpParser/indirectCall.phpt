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

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'id'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 1:1
   |  |  |  |  |  end: 1:3
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 1:4
   |  |  |  |  |  |  |  end: 1:14
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 1:4
   |  |  |  |  |  |  end: 1:14
   |  |  |  |  position: 1:1
   |  |  |  |  end: 1:15
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:16
   |  |  |  |  |  |  end: 1:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 1:16
   |  |  |  |  |  end: 1:17
   |  |  |  position: 1:1
   |  |  |  end: 1:18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:18
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 3:1
   |  |  |  |  |  |  end: 3:3
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 3:4
   |  |  |  |  |  |  |  |  end: 3:8
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:4
   |  |  |  |  |  |  |  end: 3:8
   |  |  |  |  |  position: 3:1
   |  |  |  |  |  end: 3:9
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 3:10
   |  |  |  |  |  |  |  end: 3:20
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 3:10
   |  |  |  |  |  |  end: 3:20
   |  |  |  |  position: 3:1
   |  |  |  |  end: 3:21
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 3:22
   |  |  |  |  |  |  end: 3:23
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:22
   |  |  |  |  |  end: 3:23
   |  |  |  position: 3:1
   |  |  |  end: 3:24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:24
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 5:1
   |  |  |  |  |  |  |  end: 5:3
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 5:1
   |  |  |  |  |  |  end: 5:5
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 5:1
   |  |  |  |  |  end: 5:7
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 5:8
   |  |  |  |  |  |  |  end: 5:18
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 5:8
   |  |  |  |  |  |  end: 5:18
   |  |  |  |  position: 5:1
   |  |  |  |  end: 5:19
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 4
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:20
   |  |  |  |  |  |  end: 5:21
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:20
   |  |  |  |  |  end: 5:21
   |  |  |  position: 5:1
   |  |  |  end: 5:22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:22
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:1
   |  |  |  |  |  |  |  |  end: 7:3
   |  |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'udef'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  |  |  |  |  |  |  |  end: 7:11
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  |  |  |  |  |  |  end: 7:11
   |  |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13
   |  |  |  |  |  |  |  |  |  |  |  |  |  end: 7:17
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 7:13
   |  |  |  |  |  |  |  |  |  |  |  |  end: 7:17
   |  |  |  |  |  |  |  |  |  |  position: 7:4
   |  |  |  |  |  |  |  |  |  |  end: 7:18
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  |  position: 7:4
   |  |  |  |  |  |  |  |  |  end: 7:18
   |  |  |  |  |  |  |  position: 7:1
   |  |  |  |  |  |  |  end: 7:19
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 7:20
   |  |  |  |  |  |  |  end: 7:21
   |  |  |  |  |  |  position: 7:1
   |  |  |  |  |  |  end: 7:22
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 7:1
   |  |  |  |  |  end: 7:24
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 7:25
   |  |  |  |  |  |  |  end: 7:35
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 7:25
   |  |  |  |  |  |  end: 7:35
   |  |  |  |  position: 7:1
   |  |  |  |  end: 7:36
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 5
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 7:37
   |  |  |  |  |  |  end: 7:38
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 7:37
   |  |  |  |  |  end: 7:38
   |  |  |  position: 7:1
   |  |  |  end: 7:39
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:39
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
   |  |  |  |  |  |  |  |  |  position: 9:11
   |  |  |  |  |  |  |  |  |  end: 9:13
   |  |  |  |  |  |  |  |  default: null
   |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  position: 9:11
   |  |  |  |  |  |  |  |  end: 9:13
   |  |  |  |  |  |  uses: array (0)
   |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  position: 9:24
   |  |  |  |  |  |  |  end: 9:26
   |  |  |  |  |  |  position: 9:2
   |  |  |  |  |  |  end: 9:29
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 9:31
   |  |  |  |  |  |  |  |  end: 9:35
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 9:31
   |  |  |  |  |  |  |  end: 9:35
   |  |  |  |  |  position: 9:1
   |  |  |  |  |  end: 9:36
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 9:37
   |  |  |  |  |  |  |  end: 9:47
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 9:37
   |  |  |  |  |  |  end: 9:47
   |  |  |  |  position: 9:1
   |  |  |  |  end: 9:48
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:49
   |  |  |  |  |  |  end: 9:50
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 9:49
   |  |  |  |  |  end: 9:50
   |  |  |  position: 9:1
   |  |  |  end: 9:51
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:51
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  position: 11:2
   |  |  |  |  |  |  |  |  |  end: 11:4
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:16
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:18
   |  |  |  |  |  |  |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:21
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:25
   |  |  |  |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  |  |  |  position: 11:16
   |  |  |  |  |  |  |  |  |  |  |  end: 11:25
   |  |  |  |  |  |  |  |  |  uses: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:33
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:35
   |  |  |  |  |  |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  |  |  |  |  |  position: 11:32
   |  |  |  |  |  |  |  |  |  |  |  end: 11:35
   |  |  |  |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  |  |  |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:12
   |  |  |  |  |  |  |  |  |  |  |  end: 12:14
   |  |  |  |  |  |  |  |  |  |  if: null
   |  |  |  |  |  |  |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  position: 12:18
   |  |  |  |  |  |  |  |  |  |  |  end: 12:20
   |  |  |  |  |  |  |  |  |  |  position: 12:12
   |  |  |  |  |  |  |  |  |  |  end: 12:20
   |  |  |  |  |  |  |  |  |  position: 11:7
   |  |  |  |  |  |  |  |  |  end: 13:2
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  position: 11:2
   |  |  |  |  |  |  |  |  end: 13:2
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 11:1
   |  |  |  |  |  |  |  end: 13:5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 11:1
   |  |  |  |  |  |  end: 13:7
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 11:1
   |  |  |  |  |  end: 13:9
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 13:10
   |  |  |  |  |  |  |  end: 13:20
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 13:10
   |  |  |  |  |  |  end: 13:20
   |  |  |  |  position: 11:1
   |  |  |  |  end: 13:21
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:22
   |  |  |  |  |  |  end: 13:23
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 13:22
   |  |  |  |  |  end: 13:23
   |  |  |  position: 11:1
   |  |  |  end: 13:24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 13:24
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
   |  |  |  |  |  |  |  |  |  |  |  position: 15:2
   |  |  |  |  |  |  |  |  |  |  |  end: 15:6
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:2
   |  |  |  |  |  |  |  |  |  |  end: 15:6
   |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  position: 15:8
   |  |  |  |  |  |  |  |  |  |  |  end: 15:12
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  position: 15:8
   |  |  |  |  |  |  |  |  |  |  end: 15:12
   |  |  |  |  |  |  |  |  position: 15:1
   |  |  |  |  |  |  |  |  end: 15:13
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  position: 15:1
   |  |  |  |  |  |  |  end: 15:15
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  position: 15:16
   |  |  |  |  |  |  |  |  |  end: 15:20
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  |  position: 15:16
   |  |  |  |  |  |  |  |  end: 15:20
   |  |  |  |  |  |  position: 15:1
   |  |  |  |  |  |  end: 15:21
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  position: 15:22
   |  |  |  |  |  |  |  |  end: 15:25
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 15:22
   |  |  |  |  |  |  |  end: 15:25
   |  |  |  |  |  position: 15:1
   |  |  |  |  |  end: 15:26
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 15:27
   |  |  |  |  |  |  |  end: 15:37
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 15:27
   |  |  |  |  |  |  end: 15:37
   |  |  |  |  position: 15:1
   |  |  |  |  end: 15:38
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 15:39
   |  |  |  |  |  |  end: 15:41
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 15:39
   |  |  |  |  |  end: 15:41
   |  |  |  position: 15:1
   |  |  |  end: 15:42
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1
   |  |  end: 15:42
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  position: 17:1
   |  |  |  |  |  |  |  end: 17:5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 17:1
   |  |  |  |  |  |  end: 17:7
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  position: 17:8
   |  |  |  |  |  |  |  |  end: 17:12
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 17:8
   |  |  |  |  |  |  |  end: 17:12
   |  |  |  |  |  position: 17:1
   |  |  |  |  |  end: 17:13
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 17:14
   |  |  |  |  |  |  |  end: 17:24
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 17:14
   |  |  |  |  |  |  end: 17:24
   |  |  |  |  position: 17:1
   |  |  |  |  end: 17:25
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 12
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 17:26
   |  |  |  |  |  |  end: 17:28
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 17:26
   |  |  |  |  |  end: 17:28
   |  |  |  position: 17:1
   |  |  |  end: 17:29
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1
   |  |  end: 17:29
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'i'
   |  |  |  |  |  |  |  position: 19:2
   |  |  |  |  |  |  |  end: 19:5
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  position: 19:8
   |  |  |  |  |  |  |  end: 19:11
   |  |  |  |  |  |  position: 19:2
   |  |  |  |  |  |  end: 19:11
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 19:1
   |  |  |  |  |  end: 19:14
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 19:15
   |  |  |  |  |  |  |  end: 19:25
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 19:15
   |  |  |  |  |  |  end: 19:25
   |  |  |  |  position: 19:1
   |  |  |  |  end: 19:26
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 13
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 19:27
   |  |  |  |  |  |  end: 19:29
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 19:27
   |  |  |  |  |  end: 19:29
   |  |  |  position: 19:1
   |  |  |  end: 19:30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1
   |  |  end: 19:30
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: '\id'
   |  |  |  |  |  position: 21:1
   |  |  |  |  |  end: 21:6
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  position: 21:7
   |  |  |  |  |  |  |  end: 21:17
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 21:7
   |  |  |  |  |  |  end: 21:17
   |  |  |  |  position: 21:1
   |  |  |  |  end: 21:18
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 14
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 21:19
   |  |  |  |  |  |  end: 21:21
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 21:19
   |  |  |  |  |  end: 21:21
   |  |  |  position: 21:1
   |  |  |  end: 21:22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 21:1
   |  |  end: 21:22
   position: 1:1
   end: 21:23
