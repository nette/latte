<?php declare(strict_types=1);

// Uniform variable syntax in PHP 7 (misc)

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"string"->length(),
	"foo$bar"[0],
	"foo$bar"->length(),
	(clone $obj)->b[0](1),
	[0, 1][0] = 1,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'string'
   |  |  |  |  position: 1:1+8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 1:11+6
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 1:1+18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+18
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  |  parts: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  position: 2:2+3
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  position: 2:5+4
   |  |  |  |  position: 2:1+9
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 2:11+1
   |  |  |  position: 2:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+12
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  |  parts: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  position: 3:2+3
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  position: 3:5+4
   |  |  |  |  position: 3:1+9
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 3:12+6
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 3:1+19
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+19
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\CloneNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'obj'
   |  |  |  |  |  |  |  position: 4:8+4
   |  |  |  |  |  |  position: 4:2+10
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:15+1
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 4:1+15
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 4:17+1
   |  |  |  |  position: 4:1+18
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:20+1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 4:20+1
   |  |  |  position: 4:1+21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+21
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 5:2+1
   |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  position: 5:2+1
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 5:5+1
   |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  position: 5:5+1
   |  |  |  |  |  position: 5:1+6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 5:8+1
   |  |  |  |  position: 5:1+9
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 5:13+1
   |  |  |  byRef: false
   |  |  |  position: 5:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+13
   position: 1:1+92
