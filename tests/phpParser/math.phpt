<?php

// Mathematical operators

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* unary ops */
	~$a,
	+$a,
	-$a,

	/* binary ops */
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
   items: array (20)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:2 (offset 17)
   |  |  |  operator: '~'
   |  |  |  position: 2:1 (offset 16)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 16)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:2 (offset 22)
   |  |  |  operator: '+'
   |  |  |  position: 3:1 (offset 21)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 21)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:2 (offset 27)
   |  |  |  operator: '-'
   |  |  |  position: 4:1 (offset 26)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 26)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1 (offset 49)
   |  |  |  operator: '&'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:6 (offset 54)
   |  |  |  position: 7:1 (offset 49)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 7:1 (offset 49)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:1 (offset 58)
   |  |  |  operator: '^'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 8:6 (offset 63)
   |  |  |  position: 8:1 (offset 58)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 8:1 (offset 58)
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 9:1 (offset 67)
   |  |  |  operator: '.'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 9:6 (offset 72)
   |  |  |  position: 9:1 (offset 67)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 9:1 (offset 67)
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1 (offset 76)
   |  |  |  operator: '/'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 10:6 (offset 81)
   |  |  |  position: 10:1 (offset 76)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 10:1 (offset 76)
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1 (offset 85)
   |  |  |  operator: '-'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 11:6 (offset 90)
   |  |  |  position: 11:1 (offset 85)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 11:1 (offset 85)
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 12:1 (offset 94)
   |  |  |  operator: '%'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 12:6 (offset 99)
   |  |  |  position: 12:1 (offset 94)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 12:1 (offset 94)
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 13:1 (offset 103)
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 13:6 (offset 108)
   |  |  |  position: 13:1 (offset 103)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 13:1 (offset 103)
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 14:1 (offset 112)
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 14:6 (offset 117)
   |  |  |  position: 14:1 (offset 112)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 14:1 (offset 112)
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 15:1 (offset 121)
   |  |  |  operator: '<<'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 15:7 (offset 127)
   |  |  |  position: 15:1 (offset 121)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 15:1 (offset 121)
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 16:1 (offset 131)
   |  |  |  operator: '>>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 16:7 (offset 137)
   |  |  |  position: 16:1 (offset 131)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 16:1 (offset 131)
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 17:1 (offset 141)
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 17:7 (offset 147)
   |  |  |  position: 17:1 (offset 141)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 17:1 (offset 141)
   |  |  unpack: false
   |  14 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 20:1 (offset 172)
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 20:6 (offset 177)
   |  |  |  |  position: 20:1 (offset 172)
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 20:11 (offset 182)
   |  |  |  position: 20:1 (offset 172)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 20:1 (offset 172)
   |  |  unpack: false
   |  15 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 21:1 (offset 186)
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 21:7 (offset 192)
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 21:12 (offset 197)
   |  |  |  |  position: 21:7 (offset 192)
   |  |  |  position: 21:1 (offset 186)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 21:1 (offset 186)
   |  |  unpack: false
   |  16 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 24:1 (offset 220)
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 24:6 (offset 225)
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 24:11 (offset 230)
   |  |  |  |  position: 24:6 (offset 225)
   |  |  |  position: 24:1 (offset 220)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 24:1 (offset 220)
   |  |  unpack: false
   |  17 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 25:2 (offset 235)
   |  |  |  |  operator: '+'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 25:7 (offset 240)
   |  |  |  |  position: 25:2 (offset 235)
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 25:13 (offset 246)
   |  |  |  position: 25:1 (offset 234)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 25:1 (offset 234)
   |  |  unpack: false
   |  18 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 28:1 (offset 272)
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 28:7 (offset 278)
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 28:13 (offset 284)
   |  |  |  |  position: 28:7 (offset 278)
   |  |  |  position: 28:1 (offset 272)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 28:1 (offset 272)
   |  |  unpack: false
   |  19 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 29:2 (offset 289)
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 29:8 (offset 295)
   |  |  |  |  position: 29:2 (offset 289)
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 29:15 (offset 302)
   |  |  |  position: 29:1 (offset 288)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 29:1 (offset 288)
   |  |  unpack: false
   position: null
