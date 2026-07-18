<?php declare(strict_types=1);

// New expression dereferencing

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	(new A)->b,
	(new A)->b(),
	(new A)['b'],
	(new A)['b']['c'],
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 1:6
   |  |  |  |  |  end: 1:7
   |  |  |  |  args: array (0)
   |  |  |  |  position: 1:2
   |  |  |  |  end: 1:7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:10
   |  |  |  |  end: 1:11
   |  |  |  nullsafe: false
   |  |  |  position: 1:1
   |  |  |  end: 1:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:11
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 2:6
   |  |  |  |  |  end: 2:7
   |  |  |  |  args: array (0)
   |  |  |  |  position: 2:2
   |  |  |  |  end: 2:7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:10
   |  |  |  |  end: 2:11
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 2:1
   |  |  |  end: 2:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:13
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 3:6
   |  |  |  |  |  end: 3:7
   |  |  |  |  args: array (0)
   |  |  |  |  position: 3:2
   |  |  |  |  end: 3:7
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  position: 3:9
   |  |  |  |  end: 3:12
   |  |  |  position: 3:1
   |  |  |  end: 3:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:13
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:6
   |  |  |  |  |  |  end: 4:7
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 4:2
   |  |  |  |  |  end: 4:7
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 4:9
   |  |  |  |  |  end: 4:12
   |  |  |  |  position: 4:1
   |  |  |  |  end: 4:13
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 4:14
   |  |  |  |  end: 4:17
   |  |  |  position: 4:1
   |  |  |  end: 4:18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:18
   position: 1:1
   end: 4:19
