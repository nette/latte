<?php

// Interpolated strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"$A",
	"$A->B",
	"$A[B]",
	"$A[0]",
	"$A[1234]",
	"$A[9223372036854775808]",
	"$A[000]",
	"$A[0x0]",
	"$A[0b0]",
	"$A[$B]",
	"{$A}",
	"{$A['B']}",
	"\{$A}",
	"\{ $A }",
	"\\{$A}",
	"\\{ $A }",
	"$$A[B]",
	"A $B C",
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (18)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 1:2 (offset 1)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 2:2 (offset 7)
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 2:6 (offset 11)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 2:2 (offset 7)
   |  |  |  position: 2:1 (offset 6)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 6)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 3:2 (offset 16)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 3:5 (offset 19)
   |  |  |  |  |  position: 3:2 (offset 16)
   |  |  |  position: 3:1 (offset 15)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 15)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 4:2 (offset 25)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:5 (offset 28)
   |  |  |  |  |  position: 4:2 (offset 25)
   |  |  |  position: 4:1 (offset 24)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 24)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 5:2 (offset 34)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1234
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:5 (offset 37)
   |  |  |  |  |  position: 5:2 (offset 34)
   |  |  |  position: 5:1 (offset 33)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 33)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 6:2 (offset 46)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '9223372036854775808'
   |  |  |  |  |  |  position: 6:5 (offset 49)
   |  |  |  |  |  position: 6:2 (offset 46)
   |  |  |  position: 6:1 (offset 45)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 45)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 7:2 (offset 73)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '000'
   |  |  |  |  |  |  position: 7:5 (offset 76)
   |  |  |  |  |  position: 7:2 (offset 73)
   |  |  |  position: 7:1 (offset 72)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 72)
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 8:2 (offset 84)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0x0'
   |  |  |  |  |  |  position: 8:5 (offset 87)
   |  |  |  |  |  position: 8:2 (offset 84)
   |  |  |  position: 8:1 (offset 83)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 83)
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 9:2 (offset 95)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0b0'
   |  |  |  |  |  |  position: 9:5 (offset 98)
   |  |  |  |  |  position: 9:2 (offset 95)
   |  |  |  position: 9:1 (offset 94)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 94)
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 10:2 (offset 106)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 10:5 (offset 109)
   |  |  |  |  |  position: 10:2 (offset 106)
   |  |  |  position: 10:1 (offset 105)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 105)
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 11:3 (offset 117)
   |  |  |  position: 11:1 (offset 115)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 115)
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 12:3 (offset 125)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 12:6 (offset 128)
   |  |  |  |  |  position: 12:3 (offset 125)
   |  |  |  position: 12:1 (offset 123)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 123)
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{'
   |  |  |  |  |  position: 13:2 (offset 137)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 13:4 (offset 139)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '}'
   |  |  |  |  |  position: 13:6 (offset 141)
   |  |  |  position: 13:1 (offset 136)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1 (offset 136)
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 14:2 (offset 146)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 14:5 (offset 149)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 14:7 (offset 151)
   |  |  |  position: 14:1 (offset 145)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1 (offset 145)
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\'
   |  |  |  |  |  position: 15:2 (offset 157)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 15:5 (offset 160)
   |  |  |  position: 15:1 (offset 156)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 156)
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 16:2 (offset 167)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 16:6 (offset 171)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 16:8 (offset 173)
   |  |  |  position: 16:1 (offset 166)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1 (offset 166)
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '$'
   |  |  |  |  |  position: 17:2 (offset 179)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 17:3 (offset 180)
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 17:6 (offset 183)
   |  |  |  |  |  position: 17:3 (offset 180)
   |  |  |  position: 17:1 (offset 178)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1 (offset 178)
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: 'A '
   |  |  |  |  |  position: 18:2 (offset 189)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 18:4 (offset 191)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' C'
   |  |  |  |  |  position: 18:6 (offset 193)
   |  |  |  position: 18:1 (offset 188)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1 (offset 188)
   position: 1:1 (offset 0)
