<?php declare(strict_types=1);

// Array dereferencing

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	[1, 2, 3][2],
	[1, 2, 3][2][0][0],

	array(1, 2, 3)[2],
	array(1, 2, 3)[2][0][0],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (4)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:2
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:2
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:5
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:5
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 1:8
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:8
   |  |  |  |  position: 1:1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 1:11
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 2:5
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 2:5
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 2:8
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 2:8
   |  |  |  |  |  |  position: 2:1
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 2:11
   |  |  |  |  |  position: 2:1
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 2:14
   |  |  |  |  position: 2:1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 2:17
   |  |  |  position: 2:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 4:10
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 4:10
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 4:13
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 4:13
   |  |  |  |  position: 4:1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 4:16
   |  |  |  position: 4:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 5:10
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 5:10
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 5:13
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 5:13
   |  |  |  |  |  |  position: 5:1
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 5:16
   |  |  |  |  |  position: 5:1
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 5:19
   |  |  |  |  position: 5:1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 5:22
   |  |  |  position: 5:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   position: 1:1
