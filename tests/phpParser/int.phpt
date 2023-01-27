<?php

// Different integer syntaxes

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	0,
	1,
	0xFFF,
	0xfff,
	0XfFf,
	0777,
	0b111000111000,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 0
   |  |  |  kind: 10
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 1
   |  |  |  kind: 10
   |  |  |  position: 2:1 (offset 3)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 3)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  position: 3:1 (offset 6)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 6)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  position: 4:1 (offset 13)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 13)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  position: 5:1 (offset 20)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 20)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 511
   |  |  |  kind: 8
   |  |  |  position: 6:1 (offset 27)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 27)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  value: 3640
   |  |  |  kind: 2
   |  |  |  position: 7:1 (offset 33)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 33)
   position: 1:1 (offset 0)
