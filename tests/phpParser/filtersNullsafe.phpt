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
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  position: 1:6
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 1:4
   |  |  |  position: 1:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  position: 2:2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 2:12
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 2:10
   |  |  |  |  position: 2:2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 2:19
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 2:17
   |  |  |  position: 2:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  position: 3:2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 3:12
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 3:10
   |  |  |  |  position: 3:2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 3:18
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 3:17
   |  |  |  position: 3:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  |  position: 4:12
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  position: 4:10
   |  |  |  |  |  position: 4:2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 4:18
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 4:17
   |  |  |  |  position: 4:2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 4:28
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 4:26
   |  |  |  position: 4:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 5:2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:17
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 5:22
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 5:26
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 5:24
   |  |  |  |  |  |  |  |  position: 5:22
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:21
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:5
   |  |  |  |  position: 5:2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 5:33
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 5:32
   |  |  |  position: 5:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 6:7
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:17
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:17
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 6:23
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 6:27
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 6:25
   |  |  |  |  |  |  |  |  position: 6:23
   |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  name: 'trim'
   |  |  |  |  |  |  |  |  |  position: 6:34
   |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  nullsafe: false
   |  |  |  |  |  |  |  |  position: 6:33
   |  |  |  |  |  |  |  position: 6:22
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:21
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 6:5
   |  |  |  position: 6:2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   position: 1:1
