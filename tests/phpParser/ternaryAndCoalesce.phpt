<?php declare(strict_types=1);

// Ternary operator

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* ternary */
	$a ? $b : $c,
	$a ?: $c,

	/* precedence */
	$a ? $b : $c ? $d : $e,
	$a ? $b : ($c ? $d : $e),

	/* null coalesce */
	$a ?? $b,
	$a ?? $b ?? $c,
	$a ?? $b ? $c : $d,
	$a && $b ?? $c,

	/* short ternary */
	$a ? $b,
	$a ? $b ? $c,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:6
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 2:11
   |  |  |  position: 2:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:1
   |  |  |  if: null
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 3:7
   |  |  |  position: 3:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 6:1
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 6:6
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 6:11
   |  |  |  |  position: 6:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  position: 6:16
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'e'
   |  |  |  |  position: 6:21
   |  |  |  position: 6:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:6
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 7:12
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  position: 7:17
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'e'
   |  |  |  |  |  position: 7:22
   |  |  |  |  position: 7:12
   |  |  |  position: 7:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 10:7
   |  |  |  position: 10:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 11:7
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 11:13
   |  |  |  |  position: 11:7
   |  |  |  position: 11:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 12:1
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 12:7
   |  |  |  |  position: 12:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 12:12
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  position: 12:17
   |  |  |  position: 12:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 13:1
   |  |  |  |  operator: '&&'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 13:7
   |  |  |  |  position: 13:1
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 13:13
   |  |  |  position: 13:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 16:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 16:6
   |  |  |  else: null
   |  |  |  position: 16:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 17:1
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 17:6
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 17:11
   |  |  |  |  else: null
   |  |  |  |  position: 17:6
   |  |  |  else: null
   |  |  |  position: 17:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1
   position: 2:1
