<?php

// New expression dereferencing

declare(strict_types=1);

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
   |  |  |  |  |  position: 1:6 (offset 5)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 1:2 (offset 1)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:10 (offset 9)
   |  |  |  nullsafe: false
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 2:6 (offset 17)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 2:2 (offset 13)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:10 (offset 21)
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 2:1 (offset 12)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 12)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 3:6 (offset 31)
   |  |  |  |  args: array (0)
   |  |  |  |  position: 3:2 (offset 27)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  position: 3:9 (offset 34)
   |  |  |  position: 3:1 (offset 26)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 26)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  position: 4:6 (offset 45)
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  position: 4:2 (offset 41)
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 4:9 (offset 48)
   |  |  |  |  position: 4:1 (offset 40)
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 4:14 (offset 53)
   |  |  |  position: 4:1 (offset 40)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 40)
   position: 1:1 (offset 0)
