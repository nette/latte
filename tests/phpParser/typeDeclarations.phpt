<?php

// Type hints

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function (
		$a,
		array $b,
		callable $c,
		E $d,
	    ?Foo $e,
	    A|iterable|null $f,
	    A&B $g,
	): never { return null; }
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
   |  |  |  params: array (7)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2 (offset 12)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:2 (offset 12)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:8 (offset 23)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 3:2 (offset 17)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:2 (offset 17)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 4:11 (offset 37)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'callable'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:2 (offset 28)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:2 (offset 28)
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 5:4 (offset 44)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'E'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 5:2 (offset 42)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 5:2 (offset 42)
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  position: 6:10 (offset 57)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NullableTypeNode
   |  |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:6 (offset 53)
   |  |  |  |  |  |  position: 6:5 (offset 52)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 6:5 (offset 52)
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 7:21 (offset 81)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:5 (offset 65)
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'iterable'
   |  |  |  |  |  |  |  |  position: 7:7 (offset 67)
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'null'
   |  |  |  |  |  |  |  |  position: 7:16 (offset 76)
   |  |  |  |  |  |  position: 7:5 (offset 65)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 7:5 (offset 65)
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  position: 8:9 (offset 93)
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IntersectionTypeNode
   |  |  |  |  |  |  types: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:5 (offset 89)
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'B'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:7 (offset 91)
   |  |  |  |  |  |  position: 8:5 (offset 89)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:5 (offset 89)
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'never'
   |  |  |  |  position: 9:4 (offset 100)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 9:19 (offset 115)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   position: null
