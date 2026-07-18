<?php declare(strict_types=1);

// Array definitions

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	array(),
	array('a'),
	array('a', ),
	array('a', 'b'),
	array('a', &$b, 'c' => 'd', 'e' => &$f),

	/* short array syntax */
	[],
	[1, 2, 3],
	['a' => 'b'],

	/* modern syntax */
	[a: 'b', x: 3],
	[y : 'c'],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: 1:1
   |  |  |  end: 1:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:8
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  |  end: 2:10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:7
   |  |  |  |  |  end: 2:10
   |  |  |  position: 2:1
   |  |  |  end: 2:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:11
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  |  end: 3:10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:7
   |  |  |  |  |  end: 3:10
   |  |  |  position: 3:1
   |  |  |  end: 3:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:13
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  |  end: 4:10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:7
   |  |  |  |  |  end: 4:10
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  |  end: 4:15
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:12
   |  |  |  |  |  end: 4:15
   |  |  |  position: 4:1
   |  |  |  end: 4:16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:16
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  |  end: 5:10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:7
   |  |  |  |  |  end: 5:10
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:13
   |  |  |  |  |  |  end: 5:15
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:12
   |  |  |  |  |  end: 5:15
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  position: 5:24
   |  |  |  |  |  |  end: 5:27
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  |  end: 5:20
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:17
   |  |  |  |  |  end: 5:27
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  position: 5:37
   |  |  |  |  |  |  end: 5:39
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'e'
   |  |  |  |  |  |  position: 5:29
   |  |  |  |  |  |  end: 5:32
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:29
   |  |  |  |  |  end: 5:39
   |  |  |  position: 5:1
   |  |  |  end: 5:40
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:40
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (0)
   |  |  |  position: 8:1
   |  |  |  end: 8:3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  |  end: 8:3
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:2
   |  |  |  |  |  |  end: 9:3
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:2
   |  |  |  |  |  end: 9:3
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:5
   |  |  |  |  |  |  end: 9:6
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:5
   |  |  |  |  |  end: 9:6
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 9:8
   |  |  |  |  |  |  end: 9:9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 9:8
   |  |  |  |  |  end: 9:9
   |  |  |  position: 9:1
   |  |  |  end: 9:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:10
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 10:9
   |  |  |  |  |  |  end: 10:12
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  position: 10:2
   |  |  |  |  |  |  end: 10:5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 10:2
   |  |  |  |  |  end: 10:12
   |  |  |  position: 10:1
   |  |  |  end: 10:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:13
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  position: 13:5
   |  |  |  |  |  |  end: 13:8
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 13:2
   |  |  |  |  |  |  end: 13:3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 13:2
   |  |  |  |  |  end: 13:8
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 13:13
   |  |  |  |  |  |  end: 13:14
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 13:10
   |  |  |  |  |  |  end: 13:11
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 13:10
   |  |  |  |  |  end: 13:14
   |  |  |  position: 13:1
   |  |  |  end: 13:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1
   |  |  end: 13:15
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  position: 14:6
   |  |  |  |  |  |  end: 14:9
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'y'
   |  |  |  |  |  |  position: 14:2
   |  |  |  |  |  |  end: 14:3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 14:2
   |  |  |  |  |  end: 14:9
   |  |  |  position: 14:1
   |  |  |  end: 14:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1
   |  |  end: 14:10
   position: 1:1
   end: 14:11
