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
   |  |  |  |  |  position: 1:2
   |  |  |  |  |  end: 1:4
   |  |  |  position: 1:1
   |  |  |  end: 1:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:5
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  |  end: 2:4
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 2:6
   |  |  |  |  |  |  end: 2:7
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 2:2
   |  |  |  |  |  end: 2:7
   |  |  |  position: 2:1
   |  |  |  end: 2:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:8
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  |  end: 3:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  end: 3:6
   |  |  |  |  |  position: 3:2
   |  |  |  |  |  end: 3:7
   |  |  |  position: 3:1
   |  |  |  end: 3:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:8
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  end: 4:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 4:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 4:2
   |  |  |  |  |  end: 4:7
   |  |  |  position: 4:1
   |  |  |  end: 4:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:8
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  |  end: 5:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1234
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 5:2
   |  |  |  |  |  end: 5:10
   |  |  |  position: 5:1
   |  |  |  end: 5:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:11
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 6:2
   |  |  |  |  |  |  end: 6:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '9223372036854775808'
   |  |  |  |  |  |  position: 6:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 6:2
   |  |  |  |  |  end: 6:25
   |  |  |  position: 6:1
   |  |  |  end: 6:26
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:26
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 7:2
   |  |  |  |  |  |  end: 7:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '000'
   |  |  |  |  |  |  position: 7:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 7:2
   |  |  |  |  |  end: 7:9
   |  |  |  position: 7:1
   |  |  |  end: 7:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:10
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 8:2
   |  |  |  |  |  |  end: 8:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0x0'
   |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 8:2
   |  |  |  |  |  end: 8:9
   |  |  |  position: 8:1
   |  |  |  end: 8:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  |  end: 8:10
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 9:2
   |  |  |  |  |  |  end: 9:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0b0'
   |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 9:2
   |  |  |  |  |  end: 9:9
   |  |  |  position: 9:1
   |  |  |  end: 9:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:10
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 10:2
   |  |  |  |  |  |  end: 10:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  position: 10:5
   |  |  |  |  |  |  end: 10:7
   |  |  |  |  |  position: 10:2
   |  |  |  |  |  end: 10:8
   |  |  |  position: 10:1
   |  |  |  end: 10:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:9
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 11:3
   |  |  |  |  |  end: 11:5
   |  |  |  position: 11:1
   |  |  |  end: 11:7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 11:7
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 12:3
   |  |  |  |  |  |  end: 12:5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 12:6
   |  |  |  |  |  |  end: 12:9
   |  |  |  |  |  position: 12:3
   |  |  |  |  |  end: 12:10
   |  |  |  position: 12:1
   |  |  |  end: 12:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1
   |  |  end: 12:12
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{'
   |  |  |  |  |  position: 13:2
   |  |  |  |  |  end: 13:4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 13:4
   |  |  |  |  |  end: 13:6
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '}'
   |  |  |  |  |  position: 13:6
   |  |  |  |  |  end: 13:7
   |  |  |  position: 13:1
   |  |  |  end: 13:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1
   |  |  end: 13:8
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 14:2
   |  |  |  |  |  end: 14:5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 14:5
   |  |  |  |  |  end: 14:7
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 14:7
   |  |  |  |  |  end: 14:9
   |  |  |  position: 14:1
   |  |  |  end: 14:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1
   |  |  end: 14:10
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\'
   |  |  |  |  |  position: 15:2
   |  |  |  |  |  end: 15:4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 15:5
   |  |  |  |  |  end: 15:7
   |  |  |  position: 15:1
   |  |  |  end: 15:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1
   |  |  end: 15:9
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  position: 16:2
   |  |  |  |  |  end: 16:6
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  position: 16:6
   |  |  |  |  |  end: 16:8
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  position: 16:8
   |  |  |  |  |  end: 16:10
   |  |  |  position: 16:1
   |  |  |  end: 16:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1
   |  |  end: 16:11
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: '$'
   |  |  |  |  |  position: 17:2
   |  |  |  |  |  end: 17:3
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  position: 17:3
   |  |  |  |  |  |  end: 17:5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  position: 17:6
   |  |  |  |  |  |  end: 17:7
   |  |  |  |  |  position: 17:3
   |  |  |  |  |  end: 17:8
   |  |  |  position: 17:1
   |  |  |  end: 17:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1
   |  |  end: 17:9
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: 'A '
   |  |  |  |  |  position: 18:2
   |  |  |  |  |  end: 18:4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  position: 18:4
   |  |  |  |  |  end: 18:6
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' C'
   |  |  |  |  |  position: 18:6
   |  |  |  |  |  end: 18:8
   |  |  |  position: 18:1
   |  |  |  end: 18:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1
   |  |  end: 18:9
   position: 1:1
   end: 18:10
