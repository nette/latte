<?php declare(strict_types=1);

// Closures

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function ($a) { return $a; },
	function ($a) { return $a; },
	function ($a) use ($b) { },
	function () use ($a, &$b) { return; },
	function &($a) { return; },
	function ($a) : array { return null; },
	function () use ($a,) : \Foo\Bar { return null; },
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:11
   |  |  |  |  |  |  end: 1:13
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 1:11
   |  |  |  |  |  end: 1:13
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:24
   |  |  |  |  end: 1:26
   |  |  |  position: 1:1
   |  |  |  end: 1:29
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:29
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:11
   |  |  |  |  |  |  end: 2:13
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:11
   |  |  |  |  |  end: 2:13
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:24
   |  |  |  |  end: 2:26
   |  |  |  position: 2:1
   |  |  |  end: 2:29
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:29
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:11
   |  |  |  |  |  |  end: 3:13
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:11
   |  |  |  |  |  end: 3:13
   |  |  |  uses: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:20
   |  |  |  |  |  |  end: 3:22
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 3:20
   |  |  |  |  |  end: 3:22
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  position: 3:1
   |  |  |  end: 3:27
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:27
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:18
   |  |  |  |  |  |  end: 4:20
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 4:18
   |  |  |  |  |  end: 4:20
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:23
   |  |  |  |  |  |  end: 4:25
   |  |  |  |  |  byRef: true
   |  |  |  |  |  position: 4:22
   |  |  |  |  |  end: 4:25
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  position: 4:1
   |  |  |  end: 4:38
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:38
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: true
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:12
   |  |  |  |  |  |  end: 5:14
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 5:12
   |  |  |  |  |  end: 5:14
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  position: 5:1
   |  |  |  end: 5:27
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:27
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 6:11
   |  |  |  |  |  |  end: 6:13
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 6:11
   |  |  |  |  |  end: 6:13
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'array'
   |  |  |  |  position: 6:17
   |  |  |  |  end: 6:22
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 6:32
   |  |  |  |  end: 6:36
   |  |  |  position: 6:1
   |  |  |  end: 6:39
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:39
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 7:18
   |  |  |  |  |  |  end: 7:20
   |  |  |  |  |  byRef: false
   |  |  |  |  |  position: 7:18
   |  |  |  |  |  end: 7:20
   |  |  |  returnType: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'Foo\Bar'
   |  |  |  |  kind: 2
   |  |  |  |  position: 7:25
   |  |  |  |  end: 7:33
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 7:43
   |  |  |  |  end: 7:47
   |  |  |  position: 7:1
   |  |  |  end: 7:50
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:50
   position: 1:1
   end: 7:51
