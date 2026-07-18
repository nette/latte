<?php declare(strict_types=1);

// UVS isset() on temporaries

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	isset(([0, 1] + [])[0]),
	isset(['a' => 'b']->a),
	isset("str"->a),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  |  position: 1:9
   |  |  |  |  |  |  |  |  |  |  end: 1:10
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  position: 1:9
   |  |  |  |  |  |  |  |  |  end: 1:10
   |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  |  position: 1:12
   |  |  |  |  |  |  |  |  |  |  end: 1:13
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  position: 1:12
   |  |  |  |  |  |  |  |  |  end: 1:13
   |  |  |  |  |  |  |  position: 1:8
   |  |  |  |  |  |  |  end: 1:14
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  |  position: 1:17
   |  |  |  |  |  |  |  end: 1:19
   |  |  |  |  |  |  position: 1:8
   |  |  |  |  |  |  end: 1:19
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:21
   |  |  |  |  |  |  end: 1:22
   |  |  |  |  |  position: 1:7
   |  |  |  |  |  end: 1:23
   |  |  |  position: 1:1
   |  |  |  end: 1:24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:24
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  |  |  position: 2:15
   |  |  |  |  |  |  |  |  |  end: 2:18
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  |  |  position: 2:8
   |  |  |  |  |  |  |  |  |  end: 2:11
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 2:8
   |  |  |  |  |  |  |  |  end: 2:18
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  |  end: 2:19
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:21
   |  |  |  |  |  |  end: 2:22
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 2:7
   |  |  |  |  |  end: 2:22
   |  |  |  position: 2:1
   |  |  |  end: 2:23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:23
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'str'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  |  end: 3:12
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:14
   |  |  |  |  |  |  end: 3:15
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 3:7
   |  |  |  |  |  end: 3:15
   |  |  |  position: 3:1
   |  |  |  end: 3:16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:16
   position: 1:1
   end: 3:17
