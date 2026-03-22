<?php declare(strict_types=1);

// List destructing with keys

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	list('a' => $b) = ['a' => 'b'],
	list('a' => list($b => $c), 'd' => $e) = $x,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 1:13+2
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 1:6+3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 1:6+9
   |  |  |  |  position: 1:1+15
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  position: 1:27+3
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 1:20+3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:20+10
   |  |  |  |  position: 1:19+12
   |  |  |  byRef: false
   |  |  |  position: 1:1+30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+30
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  |  position: 2:24+2
   |  |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  |  |  |  position: 2:18+2
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  position: 2:18+8
   |  |  |  |  |  |  |  position: 2:13+14
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 2:6+3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:6+21
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  |  position: 2:36+2
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  position: 2:29+3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:29+9
   |  |  |  |  position: 2:1+38
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:42+2
   |  |  |  byRef: false
   |  |  |  position: 2:1+43
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+43
   position: 1:1+76
