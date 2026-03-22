<?php declare(strict_types=1);

// Interpolated strings

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
   |  |  |  |  |  position: 1:2+2
   |  |  |  position: 1:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+4
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 2:2+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 2:6+1
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 2:2+5
   |  |  |  position: 2:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+7
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 3:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 3:5+1
   |  |  |  |  |  position: 3:2+5
   |  |  |  position: 3:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+7
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 4:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:5+1
   |  |  |  |  |  position: 4:2+5
   |  |  |  position: 4:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+7
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 5:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1234
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:5+4
   |  |  |  |  |  position: 5:2+8
   |  |  |  position: 5:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+10
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 6:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '9223372036854775808'
   |  |  |  |  |  |  position: 6:5+19
   |  |  |  |  |  position: 6:2+23
   |  |  |  position: 6:1+25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+25
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 7:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '000'
   |  |  |  |  |  |  position: 7:5+3
   |  |  |  |  |  position: 7:2+7
   |  |  |  position: 7:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+9
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 8:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0x0'
   |  |  |  |  |  |  position: 8:5+3
   |  |  |  |  |  position: 8:2+7
   |  |  |  position: 8:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+9
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 9:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0b0'
   |  |  |  |  |  |  position: 9:5+3
   |  |  |  |  |  position: 9:2+7
   |  |  |  position: 9:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+9
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 10:2+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 10:5+2
   |  |  |  |  |  position: 10:2+6
   |  |  |  position: 10:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1+8
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 11:3+2
   |  |  |  position: 11:1+6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+6
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 12:3+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 12:6+3
   |  |  |  |  |  position: 12:3+7
   |  |  |  position: 12:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1+11
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{'
   |  |  |  |  |  position: 13:2+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 13:4+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '}'
   |  |  |  |  |  position: 13:6+1
   |  |  |  position: 13:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1+7
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 14:2+3
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 14:5+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 14:7+2
   |  |  |  position: 14:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1+9
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\'
   |  |  |  |  |  position: 15:2+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 15:5+2
   |  |  |  position: 15:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+8
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 16:2+4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 16:6+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 16:8+2
   |  |  |  position: 16:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1+10
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '$'
   |  |  |  |  |  position: 17:2+1
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 17:3+2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 17:6+1
   |  |  |  |  |  position: 17:3+5
   |  |  |  position: 17:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1+8
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: 'A '
   |  |  |  |  |  position: 18:2+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 18:4+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' C'
   |  |  |  |  |  position: 18:6+2
   |  |  |  position: 18:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1+8
   position: 1:1+197
