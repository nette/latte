<?php declare(strict_types=1);

// Literals

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	true,
	false,
	null,

	True,
	False,
	Null,

	TRUE,
	FALSE,
	NULL,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (9)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 1:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+4
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 2:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+5
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 3:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+4
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 5:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+4
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 6:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+5
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 7:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+4
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 9:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+4
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 10:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1+5
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 11:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+4
   position: 1:1+58
