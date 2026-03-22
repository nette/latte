<?php declare(strict_types=1);

// string dereferencing

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"abc"[2],
	"abc"[2][0][0],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (2)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'abc'
   |  |  |  |  position: 1:1+5
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  position: 1:7+1
   |  |  |  position: 1:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+8
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'abc'
   |  |  |  |  |  |  position: 2:1+5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 2:7+1
   |  |  |  |  |  position: 2:1+8
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 2:10+1
   |  |  |  |  position: 2:1+11
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 2:13+1
   |  |  |  position: 2:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+14
   position: 1:1+25
