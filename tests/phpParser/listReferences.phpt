<?php

// List reference assignments (PHP 7.3)

declare(strict_types=1);

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
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 1:7 (offset 6)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 1:6 (offset 5)
   |  |  |  |  |  |  unpack: false
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 1:13 (offset 12)
   |  |  |  byRef: false
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 2:14 (offset 29)
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'k'
   |  |  |  |  |  |  |  position: 2:6 (offset 21)
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 2:6 (offset 21)
   |  |  |  |  |  |  unpack: false
   |  |  |  |  position: 2:1 (offset 16)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:20 (offset 35)
   |  |  |  byRef: false
   |  |  |  position: 2:1 (offset 16)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 16)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 3:3 (offset 41)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 3:2 (offset 40)
   |  |  |  |  |  |  unpack: false
   |  |  |  |  position: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 3:9 (offset 47)
   |  |  |  byRef: false
   |  |  |  position: 3:1 (offset 39)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 39)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'v'
   |  |  |  |  |  |  |  position: 4:10 (offset 60)
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'k'
   |  |  |  |  |  |  |  position: 4:2 (offset 52)
   |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  position: 4:2 (offset 52)
   |  |  |  |  |  |  unpack: false
   |  |  |  |  position: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 4:16 (offset 66)
   |  |  |  byRef: false
   |  |  |  position: 4:1 (offset 51)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 51)
   |  |  unpack: false
   position: null
