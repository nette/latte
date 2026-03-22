<?php declare(strict_types=1);

// Filters

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a|upper),
	($a . $b | upper|truncate),
	($a |truncate: 10, 20|trim),
	($a |truncate: 10, (20|round)|trim),
	($a |truncate: a: 10, b: true),
	($a |truncate( a: 10, b: true)),
	($a |truncate( a: 10, )),
	($a |truncate()),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (8)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  position: 1:5+5
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 1:4+6
   |  |  |  position: 1:2+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+10
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2+2
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7+2
   |  |  |  |  |  position: 2:2+7
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 2:12+5
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 2:10+7
   |  |  |  |  position: 2:2+15
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 2:18+8
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 2:17+9
   |  |  |  position: 2:2+24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+26
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:2+2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 3:6+8
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 3:16+2
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:16+2
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 3:20+2
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 3:20+2
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 3:5+17
   |  |  |  |  position: 3:2+20
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 3:23+4
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 3:22+5
   |  |  |  position: 3:2+25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+27
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 4:2+2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 4:6+8
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 4:16+2
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 4:16+2
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  position: 4:21+2
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 4:24+5
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: false
   |  |  |  |  |  |  |  |  |  position: 4:23+6
   |  |  |  |  |  |  |  |  position: 4:21+8
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 4:20+10
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 4:5+25
   |  |  |  |  position: 4:2+28
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 4:31+4
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 4:30+5
   |  |  |  position: 4:2+33
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+35
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 5:6+8
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 5:19+2
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 5:16+1
   |  |  |  |  |  |  position: 5:16+5
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  |  |  |  |  value: true
   |  |  |  |  |  |  |  position: 5:26+4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 5:23+1
   |  |  |  |  |  |  position: 5:23+7
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 5:5+25
   |  |  |  position: 5:2+28
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+30
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 6:6+8
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:19+2
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 6:16+1
   |  |  |  |  |  |  position: 6:16+5
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  |  |  |  |  value: true
   |  |  |  |  |  |  |  position: 6:26+4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 6:23+1
   |  |  |  |  |  |  position: 6:23+7
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 6:5+26
   |  |  |  position: 6:2+29
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+31
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 7:6+8
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 7:19+2
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 7:16+1
   |  |  |  |  |  |  position: 7:16+5
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 7:5+19
   |  |  |  position: 7:2+22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+24
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 8:6+8
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 8:5+11
   |  |  |  position: 8:2+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+16
   position: 1:1+214
