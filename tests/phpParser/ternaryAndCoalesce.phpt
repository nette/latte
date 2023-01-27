<?php

// Ternary operator

declare(strict_types=1);

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
   |  |  |  |  position: 2:1 (offset 14)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:6 (offset 19)
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 2:11 (offset 24)
   |  |  |  position: 2:1 (offset 14)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 14)
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:1 (offset 28)
   |  |  |  if: null
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 3:7 (offset 34)
   |  |  |  position: 3:1 (offset 28)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1 (offset 28)
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 6:1 (offset 56)
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 6:6 (offset 61)
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 6:11 (offset 66)
   |  |  |  |  position: 6:1 (offset 56)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  position: 6:16 (offset 71)
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'e'
   |  |  |  |  position: 6:21 (offset 76)
   |  |  |  position: 6:1 (offset 56)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 56)
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1 (offset 80)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:6 (offset 85)
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 7:12 (offset 91)
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  position: 7:17 (offset 96)
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'e'
   |  |  |  |  |  position: 7:22 (offset 101)
   |  |  |  |  position: 7:12 (offset 91)
   |  |  |  position: 7:1 (offset 80)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 80)
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1 (offset 127)
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 10:7 (offset 133)
   |  |  |  position: 10:1 (offset 127)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 127)
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1 (offset 137)
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 11:7 (offset 143)
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 11:13 (offset 149)
   |  |  |  |  position: 11:7 (offset 143)
   |  |  |  position: 11:1 (offset 137)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 137)
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 12:1 (offset 153)
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 12:7 (offset 159)
   |  |  |  |  position: 12:1 (offset 153)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 12:12 (offset 164)
   |  |  |  else: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  position: 12:17 (offset 169)
   |  |  |  position: 12:1 (offset 153)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 153)
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 13:1 (offset 173)
   |  |  |  |  operator: '&&'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 13:7 (offset 179)
   |  |  |  |  position: 13:1 (offset 173)
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 13:13 (offset 185)
   |  |  |  position: 13:1 (offset 173)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1 (offset 173)
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 16:1 (offset 210)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 16:6 (offset 215)
   |  |  |  else: null
   |  |  |  position: 16:1 (offset 210)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1 (offset 210)
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 17:1 (offset 219)
   |  |  |  if: Latte\Compiler\Nodes\Php\Expression\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 17:6 (offset 224)
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 17:11 (offset 229)
   |  |  |  |  else: null
   |  |  |  |  position: 17:6 (offset 224)
   |  |  |  else: null
   |  |  |  position: 17:1 (offset 219)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1 (offset 219)
   position: 2:1 (offset 14)
