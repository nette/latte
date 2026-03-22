<?php declare(strict_types=1);

// Arrow Functions

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	fn(bool $a) => $a,
	fn($x = 42) => $x,
	fn&($x) => $x,
	fn($x, ...$rest) => $rest,
	fn(): int => $x,

	fn($a, $b) => $a and $b,
	fn($a, $b) => $a && $b,
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
   |  |  |  |  |  |  position: 1:9+2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'bool'
   |  |  |  |  |  |  position: 1:4+4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 1:4+7
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:16+2
   |  |  |  position: 1:1+17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+17
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 2:4+2
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 42
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 2:9+2
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:4+7
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:16+2
   |  |  |  position: 2:1+17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+17
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: true
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 3:5+2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:5+2
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 3:12+2
   |  |  |  position: 3:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+13
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 4:4+2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:4+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'rest'
   |  |  |  |  |  |  position: 4:11+5
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: true
   |  |  |  |  |  position: 4:8+8
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'rest'
   |  |  |  |  position: 4:21+5
   |  |  |  position: 4:1+25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+25
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'int'
   |  |  |  |  position: 5:7+3
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 5:14+2
   |  |  |  position: 5:1+15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+15
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  |  byRef: false
   |  |  |  |  params: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 7:4+2
   |  |  |  |  |  |  default: null
   |  |  |  |  |  |  type: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  position: 7:4+2
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 7:8+2
   |  |  |  |  |  |  default: null
   |  |  |  |  |  |  type: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  position: 7:8+2
   |  |  |  |  uses: array (0)
   |  |  |  |  returnType: null
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 7:15+2
   |  |  |  |  position: 7:1+16
   |  |  |  operator: 'and'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:22+2
   |  |  |  position: 7:1+23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+23
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 8:4+2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:4+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 8:8+2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:8+2
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 8:15+2
   |  |  |  |  operator: '&&'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 8:21+2
   |  |  |  |  position: 8:15+8
   |  |  |  position: 8:1+22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+22
   position: 1:1+146
