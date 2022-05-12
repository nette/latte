<?php

// Default parameter values

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function (
	    $b = null,
	    $c = 'foo',
	    $d = A::B,
	    $f = +1,
	    $g = -1.0,
	    $h = array(),
	    $i = [],
	    $j = ['foo'],
	    $k = ['foo', 'bar' => 'baz'],
	    $l = new Foo,
	) { return null; }
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (1)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (10)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:5 (offset 15)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  position: 2:10 (offset 20)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:5 (offset 15)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 3:5 (offset 30)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  position: 3:10 (offset 35)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:5 (offset 30)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 4:5 (offset 46)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 4:10 (offset 51)
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  position: 4:13 (offset 54)
   |  |  |  |  |  |  position: 4:10 (offset 51)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:5 (offset 46)
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 5:5 (offset 61)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 5:11 (offset 67)
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  position: 5:10 (offset 66)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 5:5 (offset 61)
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  position: 6:5 (offset 74)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  |  |  |  |  value: 1.0
   |  |  |  |  |  |  |  position: 6:11 (offset 80)
   |  |  |  |  |  |  operator: '-'
   |  |  |  |  |  |  position: 6:10 (offset 79)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 6:5 (offset 74)
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'h'
   |  |  |  |  |  |  position: 7:5 (offset 89)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  position: 7:10 (offset 94)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 7:5 (offset 89)
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'i'
   |  |  |  |  |  |  position: 8:5 (offset 107)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  position: 8:10 (offset 112)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:5 (offset 107)
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'j'
   |  |  |  |  |  |  position: 9:5 (offset 120)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 9:11 (offset 126)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 9:11 (offset 126)
   |  |  |  |  |  |  position: 9:10 (offset 125)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 9:5 (offset 120)
   |  |  |  |  8 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'k'
   |  |  |  |  |  |  position: 10:5 (offset 138)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 10:11 (offset 144)
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 10:11 (offset 144)
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'baz'
   |  |  |  |  |  |  |  |  |  position: 10:27 (offset 160)
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  position: 10:18 (offset 151)
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 10:18 (offset 151)
   |  |  |  |  |  |  position: 10:10 (offset 143)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 10:5 (offset 138)
   |  |  |  |  9 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'l'
   |  |  |  |  |  |  position: 11:5 (offset 172)
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 11:14 (offset 181)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 11:10 (offset 177)
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 11:5 (offset 172)
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 12:12 (offset 197)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   position: null
