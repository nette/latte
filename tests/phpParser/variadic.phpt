<?php

// Variadic functions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function ($a, ...$b) { return null; },
	function ($a, &...$b) { return null; },
	function ($a, Type ...$b) { return null; },
	function ($a, Type &...$b) { return null; },
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (4)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:11 (offset 10)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 1:11 (offset 10)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 1:18 (offset 17)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: true
   |  |  |  |  |  position: 1:15 (offset 14)
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 1:31 (offset 30)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:11 (offset 49)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:11 (offset 49)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:19 (offset 57)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: true
   |  |  |  |  |  variadic: true
   |  |  |  |  |  position: 2:15 (offset 53)
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 2:32 (offset 70)
   |  |  |  position: 2:1 (offset 39)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 39)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:11 (offset 89)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:11 (offset 89)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:23 (offset 101)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'Type'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 3:15 (offset 93)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: true
   |  |  |  |  |  position: 3:15 (offset 93)
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 3:36 (offset 114)
   |  |  |  position: 3:1 (offset 79)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 79)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:11 (offset 133)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:11 (offset 133)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:24 (offset 146)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'Type'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:15 (offset 137)
   |  |  |  |  |  byRef: true
   |  |  |  |  |  variadic: true
   |  |  |  |  |  position: 4:15 (offset 137)
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 4:37 (offset 159)
   |  |  |  position: 4:1 (offset 123)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 123)
   position: 1:1 (offset 0)
