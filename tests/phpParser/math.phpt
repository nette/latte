<?php declare(strict_types=1);

// Mathematical operators

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* unary ops */
	~$a,
	+$a,
	-$a,

	/* binary ops */
	$a | 1,
	$a & $b,
	$a ^ $b,
	$a . $b,
	$a / $b,
	$a - $b,
	$a % $b,
	$a * $b,
	$a + $b,
	$a << $b,
	$a >> $b,
	$a ** $b,

	/* associativity */
	$a * $b * $c,
	$a * ($b * $c),

	/* precedence */
	$a + $b * $c,
	($a + $b) * $c,

	/* pow is special */
	$a ** $b ** $c,
	($a ** $b) ** $c,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (21)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:2+2
   |  |  |  operator: '~'
   |  |  |  position: 2:1+3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+3
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:2+2
   |  |  |  operator: '+'
   |  |  |  position: 3:1+3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+3
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:2+2
   |  |  |  operator: '-'
   |  |  |  position: 4:1+3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+3
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1+2
   |  |  |  operator: '|'
   |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 7:6+1
   |  |  |  position: 7:1+6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+6
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:1+2
   |  |  |  operator: '&'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 8:6+2
   |  |  |  position: 8:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+7
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 9:1+2
   |  |  |  operator: '^'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 9:6+2
   |  |  |  position: 9:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+7
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1+2
   |  |  |  operator: '.'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 10:6+2
   |  |  |  position: 10:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1+7
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1+2
   |  |  |  operator: '/'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 11:6+2
   |  |  |  position: 11:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+7
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 12:1+2
   |  |  |  operator: '-'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 12:6+2
   |  |  |  position: 12:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1+7
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 13:1+2
   |  |  |  operator: '%'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 13:6+2
   |  |  |  position: 13:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1+7
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 14:1+2
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 14:6+2
   |  |  |  position: 14:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1+7
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 15:1+2
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 15:6+2
   |  |  |  position: 15:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+7
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 16:1+2
   |  |  |  operator: '<<'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 16:7+2
   |  |  |  position: 16:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1+8
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 17:1+2
   |  |  |  operator: '>>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 17:7+2
   |  |  |  position: 17:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1+8
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 18:1+2
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 18:7+2
   |  |  |  position: 18:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1+8
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 21:1+2
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 21:6+2
   |  |  |  |  position: 21:1+7
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 21:11+2
   |  |  |  position: 21:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 21:1+12
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 22:1+2
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 22:7+2
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 22:12+2
   |  |  |  |  position: 22:7+7
   |  |  |  position: 22:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 22:1+14
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 25:1+2
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 25:6+2
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 25:11+2
   |  |  |  |  position: 25:6+7
   |  |  |  position: 25:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 25:1+12
   |  18 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 26:2+2
   |  |  |  |  operator: '+'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 26:7+2
   |  |  |  |  position: 26:2+7
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 26:13+2
   |  |  |  position: 26:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 26:1+14
   |  19 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 29:1+2
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 29:7+2
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 29:13+2
   |  |  |  |  position: 29:7+8
   |  |  |  position: 29:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 29:1+14
   |  20 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 30:2+2
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 30:8+2
   |  |  |  |  position: 30:2+8
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 30:15+2
   |  |  |  position: 30:1+16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 30:1+16
   position: 2:1+297
