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
   |  |  |  |  |  |  |  position: 1:13
   |  |  |  |  |  |  |  end: 1:15
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 1:6
   |  |  |  |  |  |  |  end: 1:9
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 1:6
   |  |  |  |  |  |  end: null
   |  |  |  |  position: 1:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  position: 1:27
   |  |  |  |  |  |  |  end: 1:30
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 1:20
   |  |  |  |  |  |  |  end: 1:23
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 1:20
   |  |  |  |  |  |  end: 1:30
   |  |  |  |  position: 1:19
   |  |  |  |  end: 1:31
   |  |  |  byRef: false
   |  |  |  position: 1:1
   |  |  |  end: 1:31
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:31
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
   |  |  |  |  |  |  |  |  |  |  position: 2:24
   |  |  |  |  |  |  |  |  |  |  end: 2:26
   |  |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  |  |  |  position: 2:18
   |  |  |  |  |  |  |  |  |  |  end: 2:20
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  position: 2:18
   |  |  |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  |  position: 2:13
   |  |  |  |  |  |  |  end: null
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  position: 2:6
   |  |  |  |  |  |  |  end: 2:9
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:6
   |  |  |  |  |  |  end: null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ListItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  |  position: 2:36
   |  |  |  |  |  |  |  end: 2:38
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  position: 2:29
   |  |  |  |  |  |  |  end: 2:32
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  position: 2:29
   |  |  |  |  |  |  end: null
   |  |  |  |  position: 2:1
   |  |  |  |  end: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:42
   |  |  |  |  end: 2:44
   |  |  |  byRef: false
   |  |  |  position: 2:1
   |  |  |  end: 2:44
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:44
   position: 1:1
   end: 2:45
