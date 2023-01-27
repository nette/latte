<?php

// Different integer syntaxes

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	6.674_083e-11,
	299_792_458,
	0xCAFE_F00D,
	0b0101_1111,
	0137_041,

	/* already a valid constant name */
	_100,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\FloatNode
   |  |  |  value: 6.674083e-11
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 299792458
   |  |  |  kind: 10
   |  |  |  position: 2:1 (offset 15)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 15)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 3405705229
   |  |  |  kind: 16
   |  |  |  position: 3:1 (offset 28)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 28)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 95
   |  |  |  kind: 2
   |  |  |  position: 4:1 (offset 41)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 41)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 48673
   |  |  |  kind: 8
   |  |  |  position: 5:1 (offset 54)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 54)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ConstantFetchNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => '_100'
   |  |  |  |  kind: 1
   |  |  |  |  position: 8:1 (offset 101)
   |  |  |  position: 8:1 (offset 101)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 101)
   position: 1:1 (offset 0)
