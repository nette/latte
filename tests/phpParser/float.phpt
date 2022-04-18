<?php

// Different float syntaxes

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	0.0,
	0.,
	.0,
	0e0,
	0E0,
	0e+0,
	0e-0,
	30.20e10,
	300.200e100,
	1e10000,

	/* various integer -> float overflows */
	/* (all are actually the same number, just in different representations) */
	18446744073709551615,
	0xFFFFFFFFFFFFFFFF,
	01777777777777777777777,
	0b1111111111111111111111111111111111111111111111111111111111111111,
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
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 2:1 (offset 5)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 5)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 3:1 (offset 9)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 9)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 4:1 (offset 13)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 13)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 5:1 (offset 18)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 5:1 (offset 18)
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 6:1 (offset 23)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 6:1 (offset 23)
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 0.0
   |  |  |  position: 7:1 (offset 29)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 7:1 (offset 29)
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 302000000000.0
   |  |  |  position: 8:1 (offset 35)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 8:1 (offset 35)
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 3.002e+102
   |  |  |  position: 9:1 (offset 45)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 9:1 (offset 45)
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: INF
   |  |  |  position: 10:1 (offset 58)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 10:1 (offset 58)
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 1.8446744073709552e+19
   |  |  |  position: 14:1 (offset 185)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 14:1 (offset 185)
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 1.8446744073709552e+19
   |  |  |  position: 15:1 (offset 207)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 15:1 (offset 207)
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 1.8446744073709552e+19
   |  |  |  position: 16:1 (offset 227)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 16:1 (offset 227)
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 1.8446744073709552e+19
   |  |  |  position: 17:1 (offset 252)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 17:1 (offset 252)
   |  |  unpack: false
   position: null
