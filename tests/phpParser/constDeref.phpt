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

	A::B[0],
	A::B[0][1][2],
	A::B->length,
	A::B->length(),
	A::B::C,
	A::B::$c,
	A::B::c(),

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
   items: array (14)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
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
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
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
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
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
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
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
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 6:1 (offset 43)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 6:4 (offset 46)
   |  |  |  |  position: 6:1 (offset 43)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 6:6 (offset 48)
   |  |  |  position: 6:1 (offset 43)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 43)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  position: 7:1 (offset 52)
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  position: 7:4 (offset 55)
   |  |  |  |  |  |  position: 7:1 (offset 52)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 7:6 (offset 57)
   |  |  |  |  |  position: 7:1 (offset 52)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 7:9 (offset 60)
   |  |  |  |  position: 7:1 (offset 52)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 7:12 (offset 63)
   |  |  |  position: 7:1 (offset 52)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 52)
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 8:1 (offset 67)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 8:4 (offset 70)
   |  |  |  |  position: 8:1 (offset 67)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 8:7 (offset 73)
   |  |  |  nullsafe: false
   |  |  |  position: 8:1 (offset 67)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 67)
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 9:1 (offset 81)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 9:4 (offset 84)
   |  |  |  |  position: 9:1 (offset 81)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 9:7 (offset 87)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 9:1 (offset 81)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 81)
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 10:1 (offset 97)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 10:4 (offset 100)
   |  |  |  |  position: 10:1 (offset 97)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'C'
   |  |  |  |  position: 10:7 (offset 103)
   |  |  |  position: 10:1 (offset 97)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 97)
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 11:1 (offset 106)
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 11:4 (offset 109)
   |  |  |  |  position: 11:1 (offset 106)
   |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 11:7 (offset 112)
   |  |  |  position: 11:1 (offset 106)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 106)
   |  10 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticCallNode
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
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 12:7 (offset 122)
   |  |  |  args: array (0)
   |  |  |  position: 12:1 (offset 116)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 116)
   |  11 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 14:1 (offset 128)
   |  |  |  |  position: 14:1 (offset 128)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 14:14 (offset 141)
   |  |  |  position: 14:1 (offset 128)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1 (offset 128)
   |  12 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCTION__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 15:1 (offset 145)
   |  |  |  |  position: 15:1 (offset 145)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 15:15 (offset 159)
   |  |  |  nullsafe: false
   |  |  |  position: 15:1 (offset 145)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 145)
   |  13 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => '__FUNCIONT__'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 16:1 (offset 167)
   |  |  |  |  position: 16:1 (offset 167)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  position: 16:15 (offset 181)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 16:1 (offset 167)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1 (offset 167)
   position: 1:1 (offset 0)
