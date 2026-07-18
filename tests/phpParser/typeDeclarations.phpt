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
   |  |  |  |  |  |  end: 2:4
   |  |  |  |  |  default: null
   |  |  |  |  |  type: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 2:2
   |  |  |  |  |  end: 2:4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:8
   |  |  |  |  |  |  end: 3:10
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  |  end: 3:7
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 3:2
   |  |  |  |  |  end: 3:10
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 4:11
   |  |  |  |  |  |  end: 4:13
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'callable'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  end: 4:10
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 4:2
   |  |  |  |  |  end: 4:13
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 5:4
   |  |  |  |  |  |  end: 5:6
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'E'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  |  end: 5:3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 5:2
   |  |  |  |  |  end: 5:6
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  position: 6:10
   |  |  |  |  |  |  end: 6:12
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NullableTypeNode
   |  |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'Foo'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 6:6
   |  |  |  |  |  |  |  end: 6:9
   |  |  |  |  |  |  position: 6:5
   |  |  |  |  |  |  end: 6:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 6:5
   |  |  |  |  |  end: 6:12
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 7:26
   |  |  |  |  |  |  end: 7:28
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (4)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  |  |  end: 7:6
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'iterable'
   |  |  |  |  |  |  |  |  position: 7:7
   |  |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 7:17
   |  |  |  |  |  |  |  |  end: 7:20
   |  |  |  |  |  |  |  3 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'null'
   |  |  |  |  |  |  |  |  position: 7:21
   |  |  |  |  |  |  |  |  end: 7:25
   |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  end: 7:25
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 7:5
   |  |  |  |  |  end: 7:28
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 8:21
   |  |  |  |  |  |  end: 8:23
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (5)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  |  |  |  end: 8:6
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:7
   |  |  |  |  |  |  |  |  end: 8:8
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'C'
   |  |  |  |  |  |  |  |  kind: 2
   |  |  |  |  |  |  |  |  position: 8:9
   |  |  |  |  |  |  |  |  end: 8:11
   |  |  |  |  |  |  |  3 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'D'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 8:14
   |  |  |  |  |  |  |  |  end: 8:15
   |  |  |  |  |  |  |  4 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'E'
   |  |  |  |  |  |  |  |  kind: 2
   |  |  |  |  |  |  |  |  position: 8:18
   |  |  |  |  |  |  |  |  end: 8:20
   |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  |  end: 8:20
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 8:5
   |  |  |  |  |  end: 8:23
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\ParameterNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  position: 9:9
   |  |  |  |  |  |  end: 9:11
   |  |  |  |  |  default: null
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IntersectionTypeNode
   |  |  |  |  |  |  types: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  |  |  |  end: 9:6
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 9:7
   |  |  |  |  |  |  |  |  end: 9:8
   |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  |  end: 9:8
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  position: 9:5
   |  |  |  |  |  end: 9:11
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'never'
   |  |  |  |  position: 10:4
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  position: 10:19
   |  |  |  |  end: 10:23
   |  |  |  position: 1:1
   |  |  |  end: 10:26
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 10:26
   position: 1:1
   end: 10:26
