<?php declare(strict_types=1);

// Type hints

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function (
		$a,
		array $b,
		callable $c,
		E $d,
	    ?Foo $e,
	    A|iterable| foo|null $f,
	    A|B|\C | D | \E $f,
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
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (8)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:8
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 4:11
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'callable'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:2
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 5:4
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'E'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 5:2
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  position: 6:10
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NullableTypeNode
   |  |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'Foo'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:6
   |  |  |  |  |  |  position: 6:5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 6:5
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 7:26
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (4)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'iterable'
   |  |  |  |  |  |  |  |  position: 7:7
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:17
   |  |  |  |  |  |  |  3 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'null'
   |  |  |  |  |  |  |  |  position: 7:21
   |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 7:5
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 8:21
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (5)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:7
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'C'
   |  |  |  |  |  |  |  |  kind: 2
   |  |  |  |  |  |  |  |  position: 8:9
   |  |  |  |  |  |  |  3 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'D'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:14
   |  |  |  |  |  |  |  4 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'E'
   |  |  |  |  |  |  |  |  kind: 2
   |  |  |  |  |  |  |  |  position: 8:18
   |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:5
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  position: 9:9
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IntersectionTypeNode
   |  |  |  |  |  |  types: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 9:7
   |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 9:5
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'never'
   |  |  |  |  position: 10:4
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 10:19
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   position: 1:1
