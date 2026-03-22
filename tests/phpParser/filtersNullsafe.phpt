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

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  position: 1:6+5
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 1:4+7
   |  |  |  position: 1:2+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+11
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
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 2:10+7
   |  |  |  |  position: 2:2+15
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 2:19+8
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 2:17+10
   |  |  |  position: 2:2+25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+27
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:2+2
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:7+2
   |  |  |  |  |  position: 3:2+7
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  position: 3:12+5
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 3:10+7
   |  |  |  |  position: 3:2+15
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 3:18+8
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 3:17+9
   |  |  |  position: 3:2+24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+26
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:2+2
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 4:7+2
   |  |  |  |  |  |  position: 4:2+7
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'upper'
   |  |  |  |  |  |  |  position: 4:12+5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  position: 4:10+7
   |  |  |  |  |  position: 4:2+15
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 4:18+8
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 4:17+9
   |  |  |  |  position: 4:2+24
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 4:28+4
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 4:26+6
   |  |  |  position: 4:2+30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+32
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 5:2+2
   |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  |  position: 5:7+8
   |  |  |  |  |  args: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  position: 5:17+2
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:17+2
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 5:22+2
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 5:26+5
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 5:24+7
   |  |  |  |  |  |  |  |  position: 5:22+9
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  position: 5:21+11
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:5+27
   |  |  |  |  position: 5:2+30
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'trim'
   |  |  |  |  |  position: 5:33+4
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 5:32+5
   |  |  |  position: 5:2+35
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+37
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:2+2
   |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  position: 6:7+8
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:17+2
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:17+2
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  position: 6:23+2
   |  |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  |  |  position: 6:27+5
   |  |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  |  |  position: 6:25+7
   |  |  |  |  |  |  |  |  position: 6:23+9
   |  |  |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  |  name: 'trim'
   |  |  |  |  |  |  |  |  |  position: 6:34+4
   |  |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  |  nullsafe: false
   |  |  |  |  |  |  |  |  position: 6:33+5
   |  |  |  |  |  |  |  position: 6:22+16
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  name: null
   |  |  |  |  |  |  position: 6:21+18
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 6:5+34
   |  |  |  position: 6:2+37
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+39
   position: 1:1+183
