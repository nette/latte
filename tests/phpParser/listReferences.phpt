<?php declare(strict_types=1);

// List reference assignments (PHP 7.3)

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	list(&$v) = $x,
	list('k' => &$v) = $x,
	[&$v] = $x,
	['k' => &$v] = $x,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 1:7
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 1:6
   |  |  |  |  position: 1:1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 1:13
   |  |  |  byRef: false
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 2:14
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'k'
   |  |  |  |  |  |  |  position: 2:6
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 2:6
   |  |  |  |  position: 2:1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:20
   |  |  |  byRef: false
   |  |  |  position: 2:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 3:3
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  position: 3:1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 3:9
   |  |  |  byRef: false
   |  |  |  position: 3:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 4:10
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'k'
   |  |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  position: 4:1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 4:16
   |  |  |  byRef: false
   |  |  |  position: 4:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   position: 1:1
