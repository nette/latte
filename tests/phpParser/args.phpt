<?php

// Arguments

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	f(),
	f($a),
	f($a, $b),
	f(&$a),
	f($a, ...$b),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'f'
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  args: array (0)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'f'
   |  |  |  |  position: 2:1 (offset 5)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:3 (offset 7)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:3 (offset 7)
   |  |  |  |  |  name: null
   |  |  |  position: 2:1 (offset 5)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 5)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'f'
   |  |  |  |  position: 3:1 (offset 12)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:3 (offset 14)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:3 (offset 14)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:7 (offset 18)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:7 (offset 18)
   |  |  |  |  |  name: null
   |  |  |  position: 3:1 (offset 12)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 12)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'f'
   |  |  |  |  position: 4:1 (offset 23)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:4 (offset 26)
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:3 (offset 25)
   |  |  |  |  |  name: null
   |  |  |  position: 4:1 (offset 23)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 23)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'f'
   |  |  |  |  position: 5:1 (offset 31)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:3 (offset 33)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:3 (offset 33)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:10 (offset 40)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  position: 5:7 (offset 37)
   |  |  |  |  |  name: null
   |  |  |  position: 5:1 (offset 31)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 5:1 (offset 31)
   |  |  unpack: false
   position: null
