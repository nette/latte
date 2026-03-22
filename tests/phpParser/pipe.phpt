<?php declare(strict_types=1);

// Pipe operator

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a |> $b |> $c,
	$a . $b |> $c . $d,
	$a |> $b == $c,
	$c == $a |> $b,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 1:1+2
   |  |  |  |  operator: '|>'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 1:7+2
   |  |  |  |  position: 1:1+8
   |  |  |  operator: '|>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 1:13+2
   |  |  |  position: 1:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+14
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 2:1+2
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 2:6+2
   |  |  |  |  position: 2:1+7
   |  |  |  operator: '|>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 2:12+2
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  position: 2:17+2
   |  |  |  |  position: 2:12+7
   |  |  |  position: 2:1+18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+18
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:1+2
   |  |  |  |  operator: '|>'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:7+2
   |  |  |  |  position: 3:1+8
   |  |  |  operator: '=='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 3:13+2
   |  |  |  position: 3:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+14
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 4:1+2
   |  |  |  operator: '=='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 4:7+2
   |  |  |  |  operator: '|>'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 4:13+2
   |  |  |  |  position: 4:7+8
   |  |  |  position: 4:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+14
   position: 1:1+67
