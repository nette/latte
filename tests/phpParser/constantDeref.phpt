<?php

// Dereferencing of constants

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	A->length,
	A->length(),
	A[0],
	A[0][1][2],
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
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 1:1 (offset 0)
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 1:4 (offset 3)
   |  |  |  nullsafe: false
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 2:1 (offset 11)
   |  |  |  |  position: 2:1 (offset 11)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 2:4 (offset 14)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 2:1 (offset 11)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 11)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 3:1 (offset 24)
   |  |  |  |  position: 3:1 (offset 24)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 3:3 (offset 26)
   |  |  |  position: 3:1 (offset 24)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 24)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 4:1 (offset 30)
   |  |  |  |  |  |  position: 4:1 (offset 30)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:3 (offset 32)
   |  |  |  |  |  position: 4:1 (offset 30)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 4:6 (offset 35)
   |  |  |  |  position: 4:1 (offset 30)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 4:9 (offset 38)
   |  |  |  position: 4:1 (offset 30)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 30)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (2)
   |  |  |  |  |  |  0 => 'x'
   |  |  |  |  |  |  1 => 'foo'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 5:1 (offset 42)
   |  |  |  |  position: 5:1 (offset 42)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 5:7 (offset 48)
   |  |  |  position: 5:1 (offset 42)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 42)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:1 (offset 53)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 7:4 (offset 56)
   |  |  |  |  position: 7:1 (offset 53)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 7:6 (offset 58)
   |  |  |  position: 7:1 (offset 53)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 53)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 8:1 (offset 62)
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  position: 8:4 (offset 65)
   |  |  |  |  |  |  position: 8:1 (offset 62)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 8:6 (offset 67)
   |  |  |  |  |  position: 8:1 (offset 62)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 8:9 (offset 70)
   |  |  |  |  position: 8:1 (offset 62)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 8:12 (offset 73)
   |  |  |  position: 8:1 (offset 62)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 62)
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 9:1 (offset 77)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 9:4 (offset 80)
   |  |  |  |  position: 9:1 (offset 77)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 9:7 (offset 83)
   |  |  |  nullsafe: false
   |  |  |  position: 9:1 (offset 77)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 77)
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 10:1 (offset 91)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 10:4 (offset 94)
   |  |  |  |  position: 10:1 (offset 91)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 10:7 (offset 97)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 10:1 (offset 91)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 91)
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 11:1 (offset 107)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 11:4 (offset 110)
   |  |  |  |  position: 11:1 (offset 107)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'C'
   |  |  |  |  position: 11:7 (offset 113)
   |  |  |  position: 11:1 (offset 107)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 107)
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 12:1 (offset 116)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 12:4 (offset 119)
   |  |  |  |  position: 12:1 (offset 116)
   |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 12:7 (offset 122)
   |  |  |  position: 12:1 (offset 116)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 116)
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 13:1 (offset 126)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 13:4 (offset 129)
   |  |  |  |  position: 13:1 (offset 126)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 13:7 (offset 132)
   |  |  |  args: array (0)
   |  |  |  position: 13:1 (offset 126)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1 (offset 126)
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  position: 15:1 (offset 138)
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'BAR'
   |  |  |  |  |  |  |  position: 15:7 (offset 144)
   |  |  |  |  |  |  position: 15:1 (offset 138)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 15:11 (offset 148)
   |  |  |  |  |  position: 15:1 (offset 138)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 15:14 (offset 151)
   |  |  |  |  position: 15:1 (offset 138)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 15:17 (offset 154)
   |  |  |  position: 15:1 (offset 138)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 138)
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 17:1 (offset 159)
   |  |  |  |  position: 17:1 (offset 159)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 17:14 (offset 172)
   |  |  |  position: 17:1 (offset 159)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1 (offset 159)
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 18:1 (offset 176)
   |  |  |  |  position: 18:1 (offset 176)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 18:15 (offset 190)
   |  |  |  nullsafe: false
   |  |  |  position: 18:1 (offset 176)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1 (offset 176)
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCIONT__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 19:1 (offset 198)
   |  |  |  |  position: 19:1 (offset 198)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 19:15 (offset 212)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 19:1 (offset 198)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1 (offset 198)
   position: 1:1 (offset 0)
