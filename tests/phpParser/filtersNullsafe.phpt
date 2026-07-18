<?php declare(strict_types=1);

// Filters

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a?|upper),
	($a . $b ?|upper?|truncate),
	($a . $b ?|upper|truncate),
	($a . $b ?|upper|truncate?|trim),
	($a ?|truncate: 10, ($c?|round)|trim),
	($a ?|truncate: 10, (($c?|round)|trim)),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:2
   |  |  |  |  end: 1:4
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  position: 1:6
   |  |  |  |  |  end: 1:11
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 1:4
   |  |  |  |  end: 1:11
   |  |  |  position: 1:2
   |  |  |  end: 1:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:12
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  |  end: 2:4
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  |  end: 2:9
   |  |  |  |  |  position: 2:2
   |  |  |  |  |  end: 2:9
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  |  end: 2:17
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 2:10
   |  |  |  |  |  end: 2:17
   |  |  |  |  position: 2:2
   |  |  |  |  end: 2:17
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 2:19
   |  |  |  |  |  end: 2:27
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 2:17
   |  |  |  |  end: 2:27
   |  |  |  position: 2:2
   |  |  |  end: 2:27
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:28
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  |  end: 3:4
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  |  end: 3:9
   |  |  |  |  |  position: 3:2
   |  |  |  |  |  end: 3:9
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 3:12
   |  |  |  |  |  |  end: 3:17
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 3:10
   |  |  |  |  |  end: 3:17
   |  |  |  |  position: 3:2
   |  |  |  |  end: 3:17
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 3:18
   |  |  |  |  |  end: 3:26
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 3:17
   |  |  |  |  end: 3:26
   |  |  |  position: 3:2
   |  |  |  end: 3:26
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:27
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  |  end: 4:4
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  |  |  end: 4:9
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  end: 4:9
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  |  |  end: 4:17
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  position: 4:10
   |  |  |  |  |  |  end: 4:17
   |  |  |  |  |  position: 4:2
   |  |  |  |  |  end: 4:17
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 4:18
   |  |  |  |  |  |  end: 4:26
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 4:17
   |  |  |  |  |  end: 4:26
   |  |  |  |  position: 4:2
   |  |  |  |  end: 4:26
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 4:28
   |  |  |  |  |  end: 4:32
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 4:26
   |  |  |  |  end: 4:32
   |  |  |  position: 4:2
   |  |  |  end: 4:32
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:33
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 5:2
   |  |  |  |  |  end: 5:4
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  |  end: 5:15
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  |  |  |  end: 5:19
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  |  |  end: 5:19
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 5:22
   |  |  |  |  |  |  |  |  |  end: 5:24
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 5:26
   |  |  |  |  |  |  |  |  |  |  end: 5:31
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 5:24
   |  |  |  |  |  |  |  |  |  end: 5:31
   |  |  |  |  |  |  |  |  position: 5:22
   |  |  |  |  |  |  |  |  end: 5:31
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:21
   |  |  |  |  |  |  |  end: 5:32
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:5
   |  |  |  |  |  end: 5:32
   |  |  |  |  position: 5:2
   |  |  |  |  end: 5:32
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 5:33
   |  |  |  |  |  end: 5:37
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 5:32
   |  |  |  |  end: 5:37
   |  |  |  position: 5:2
   |  |  |  end: 5:37
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:38
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:2
   |  |  |  |  end: 6:4
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 6:7
   |  |  |  |  |  end: 6:15
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:17
   |  |  |  |  |  |  |  end: 6:19
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:17
   |  |  |  |  |  |  end: 6:19
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 6:23
   |  |  |  |  |  |  |  |  |  end: 6:25
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 6:27
   |  |  |  |  |  |  |  |  |  |  end: 6:32
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 6:25
   |  |  |  |  |  |  |  |  |  end: 6:32
   |  |  |  |  |  |  |  |  position: 6:23
   |  |  |  |  |  |  |  |  end: 6:32
   |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  name: 'trim'
   |  |  |  |  |  |  |  |  |  position: 6:34
   |  |  |  |  |  |  |  |  |  end: 6:38
   |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  nullsafe: false
   |  |  |  |  |  |  |  |  position: 6:33
   |  |  |  |  |  |  |  |  end: 6:38
   |  |  |  |  |  |  |  position: 6:22
   |  |  |  |  |  |  |  end: 6:38
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:21
   |  |  |  |  |  |  end: 6:39
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 6:5
   |  |  |  |  end: 6:39
   |  |  |  position: 6:2
   |  |  |  end: 6:39
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:40
   position: 1:1
   end: 6:41
