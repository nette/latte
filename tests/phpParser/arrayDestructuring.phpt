<?php declare(strict_types=1);

// Array destructuring

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	[$a, $b] = [$c, $d],
	[, $a, , , $b, ,] = $foo,
	[, [[$a]], $b] = $bar,
	['a' => $b, 'b' => $a] = $baz,
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
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 1:2
   |  |  |  |  |  |  |  end: 1:4
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 1:2
   |  |  |  |  |  |  end: null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 1:6
   |  |  |  |  |  |  |  end: 1:8
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 1:6
   |  |  |  |  |  |  end: null
   |  |  |  |  position: 1:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  position: 1:13
   |  |  |  |  |  |  |  end: 1:15
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:13
   |  |  |  |  |  |  end: 1:15
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  |  position: 1:17
   |  |  |  |  |  |  |  end: 1:19
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:17
   |  |  |  |  |  |  end: 1:19
   |  |  |  |  position: 1:12
   |  |  |  |  end: 1:20
   |  |  |  byRef: false
   |  |  |  position: 1:1
   |  |  |  end: 1:20
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:20
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (6)
   |  |  |  |  |  0 => null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 2:4
   |  |  |  |  |  |  |  end: 2:6
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:4
   |  |  |  |  |  |  end: null
   |  |  |  |  |  2 => null
   |  |  |  |  |  3 => null
   |  |  |  |  |  4 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  |  |  end: 2:14
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  |  end: null
   |  |  |  |  |  5 => null
   |  |  |  |  position: 2:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 2:21
   |  |  |  |  end: 2:25
   |  |  |  byRef: false
   |  |  |  position: 2:1
   |  |  |  end: 2:25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:25
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  |  |  |  |  |  |  position: 3:6
   |  |  |  |  |  |  |  |  |  |  |  |  |  end: 3:8
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  position: 3:6
   |  |  |  |  |  |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  |  position: 3:4
   |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 3:4
   |  |  |  |  |  |  end: null
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 3:12
   |  |  |  |  |  |  |  end: 3:14
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 3:12
   |  |  |  |  |  |  end: null
   |  |  |  |  position: 3:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'bar'
   |  |  |  |  position: 3:18
   |  |  |  |  end: 3:22
   |  |  |  byRef: false
   |  |  |  position: 3:1
   |  |  |  end: 3:22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:22
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\ListNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 4:9
   |  |  |  |  |  |  |  end: 4:11
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  |  end: 4:5
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  end: null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:20
   |  |  |  |  |  |  |  end: 4:22
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  position: 4:13
   |  |  |  |  |  |  |  end: 4:16
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 4:13
   |  |  |  |  |  |  end: null
   |  |  |  |  position: 4:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'baz'
   |  |  |  |  position: 4:26
   |  |  |  |  end: 4:30
   |  |  |  byRef: false
   |  |  |  position: 4:1
   |  |  |  end: 4:30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:30
   position: 1:1
   end: 4:31
