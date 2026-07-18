<?php declare(strict_types=1);

// Dereferencing of constants

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	\A->length,
	\A->length(),
	\A[0],
	\A[0][1][2],
	x\foo[0],

	A::B[0],
	A::B[0][1][2],
	A::B->length,
	A::B->length(),
	A::B::C,
	A::B::$c,
	A::B::c(),

	$foo::BAR[2][1][0],

	__FUNCTION__[0],
	__FUNCTION__->length,
	__FUNCIONT__->length(),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (16)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 2
   |  |  |  |  |  position: 1:1
   |  |  |  |  |  end: 1:3
   |  |  |  |  position: 1:1
   |  |  |  |  end: 1:3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 1:5
   |  |  |  |  end: 1:11
   |  |  |  nullsafe: false
   |  |  |  position: 1:1
   |  |  |  end: 1:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:11
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 2
   |  |  |  |  |  position: 2:1
   |  |  |  |  |  end: 2:3
   |  |  |  |  position: 2:1
   |  |  |  |  end: 2:3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 2:5
   |  |  |  |  end: 2:11
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 2:1
   |  |  |  end: 2:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:13
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 2
   |  |  |  |  |  position: 3:1
   |  |  |  |  |  end: 3:3
   |  |  |  |  position: 3:1
   |  |  |  |  end: 3:3
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 3:4
   |  |  |  |  end: 3:5
   |  |  |  position: 3:1
   |  |  |  end: 3:6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:6
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  kind: 2
   |  |  |  |  |  |  |  position: 4:1
   |  |  |  |  |  |  |  end: 4:3
   |  |  |  |  |  |  position: 4:1
   |  |  |  |  |  |  end: 4:3
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:4
   |  |  |  |  |  |  end: 4:5
   |  |  |  |  |  position: 4:1
   |  |  |  |  |  end: 4:6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 4:7
   |  |  |  |  |  end: 4:8
   |  |  |  |  position: 4:1
   |  |  |  |  end: 4:9
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 4:10
   |  |  |  |  end: 4:11
   |  |  |  position: 4:1
   |  |  |  end: 4:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:12
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'x\foo'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 5:1
   |  |  |  |  |  end: 5:6
   |  |  |  |  position: 5:1
   |  |  |  |  end: 5:6
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 5:7
   |  |  |  |  end: 5:8
   |  |  |  position: 5:1
   |  |  |  end: 5:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:9
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:1
   |  |  |  |  |  end: 7:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 7:4
   |  |  |  |  |  end: 7:5
   |  |  |  |  position: 7:1
   |  |  |  |  end: 7:5
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 7:6
   |  |  |  |  end: 7:7
   |  |  |  position: 7:1
   |  |  |  end: 7:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:8
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 8:1
   |  |  |  |  |  |  |  end: 8:2
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  position: 8:4
   |  |  |  |  |  |  |  end: 8:5
   |  |  |  |  |  |  position: 8:1
   |  |  |  |  |  |  end: 8:5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 8:6
   |  |  |  |  |  |  end: 8:7
   |  |  |  |  |  position: 8:1
   |  |  |  |  |  end: 8:8
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 8:9
   |  |  |  |  |  end: 8:10
   |  |  |  |  position: 8:1
   |  |  |  |  end: 8:11
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 8:12
   |  |  |  |  end: 8:13
   |  |  |  position: 8:1
   |  |  |  end: 8:14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  |  end: 8:14
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 9:1
   |  |  |  |  |  end: 9:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 9:4
   |  |  |  |  |  end: 9:5
   |  |  |  |  position: 9:1
   |  |  |  |  end: 9:5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 9:7
   |  |  |  |  end: 9:13
   |  |  |  nullsafe: false
   |  |  |  position: 9:1
   |  |  |  end: 9:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:13
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 10:1
   |  |  |  |  |  end: 10:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 10:4
   |  |  |  |  |  end: 10:5
   |  |  |  |  position: 10:1
   |  |  |  |  end: 10:5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 10:7
   |  |  |  |  end: 10:13
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 10:1
   |  |  |  end: 10:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:15
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 11:1
   |  |  |  |  |  end: 11:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 11:4
   |  |  |  |  |  end: 11:5
   |  |  |  |  position: 11:1
   |  |  |  |  end: 11:5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'C'
   |  |  |  |  position: 11:7
   |  |  |  |  end: 11:8
   |  |  |  position: 11:1
   |  |  |  end: 11:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 11:8
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 12:1
   |  |  |  |  |  end: 12:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 12:4
   |  |  |  |  |  end: 12:5
   |  |  |  |  position: 12:1
   |  |  |  |  end: 12:5
   |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 12:7
   |  |  |  |  end: 12:9
   |  |  |  position: 12:1
   |  |  |  end: 12:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1
   |  |  end: 12:9
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 13:1
   |  |  |  |  |  end: 13:2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 13:4
   |  |  |  |  |  end: 13:5
   |  |  |  |  position: 13:1
   |  |  |  |  end: 13:5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 13:7
   |  |  |  |  end: 13:8
   |  |  |  args: array (0)
   |  |  |  position: 13:1
   |  |  |  end: 13:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1
   |  |  end: 13:10
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  position: 15:1
   |  |  |  |  |  |  |  end: 15:5
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'BAR'
   |  |  |  |  |  |  |  position: 15:7
   |  |  |  |  |  |  |  end: 15:10
   |  |  |  |  |  |  position: 15:1
   |  |  |  |  |  |  end: 15:10
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 15:11
   |  |  |  |  |  |  end: 15:12
   |  |  |  |  |  position: 15:1
   |  |  |  |  |  end: 15:13
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 15:14
   |  |  |  |  |  end: 15:15
   |  |  |  |  position: 15:1
   |  |  |  |  end: 15:16
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 15:17
   |  |  |  |  end: 15:18
   |  |  |  position: 15:1
   |  |  |  end: 15:19
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1
   |  |  end: 15:19
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 17:1
   |  |  |  |  |  end: 17:13
   |  |  |  |  position: 17:1
   |  |  |  |  end: 17:13
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 17:14
   |  |  |  |  end: 17:15
   |  |  |  position: 17:1
   |  |  |  end: 17:16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1
   |  |  end: 17:16
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 18:1
   |  |  |  |  |  end: 18:13
   |  |  |  |  position: 18:1
   |  |  |  |  end: 18:13
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 18:15
   |  |  |  |  end: 18:21
   |  |  |  nullsafe: false
   |  |  |  position: 18:1
   |  |  |  end: 18:21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1
   |  |  end: 18:21
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: '__FUNCIONT__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 19:1
   |  |  |  |  |  end: 19:13
   |  |  |  |  position: 19:1
   |  |  |  |  end: 19:13
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 19:15
   |  |  |  |  end: 19:21
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 19:1
   |  |  |  end: 19:23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1
   |  |  end: 19:23
   position: 1:1
   end: 19:24
