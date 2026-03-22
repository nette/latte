<?php declare(strict_types=1);

// Arguments

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
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'f'
   |  |  |  |  kind: 1
   |  |  |  |  position: 1:1+1
   |  |  |  args: array (0)
   |  |  |  position: 1:1+3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+3
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'f'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:1+1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:3+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:3+2
   |  |  |  position: 2:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+5
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'f'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1+1
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:3+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:3+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:7+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:7+2
   |  |  |  position: 3:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+9
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'f'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1+1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:4+2
   |  |  |  |  |  byRef: true
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 4:3+3
   |  |  |  position: 4:1+6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+6
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'f'
   |  |  |  |  kind: 1
   |  |  |  |  position: 5:1+1
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:3+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:3+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:10+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:7+5
   |  |  |  position: 5:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+12
   position: 1:1+44
