<?php declare(strict_types=1);

// Variable syntaxes

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a,
	${'a'},
	${foo()},
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  name: 'a'
   |  |  |  position: 1:1
   |  |  |  end: 1:3
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:3
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'a'
   |  |  |  |  position: 2:3
   |  |  |  |  end: 2:6
   |  |  |  position: 2:1
   |  |  |  end: 2:7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:7
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'foo'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 3:3
   |  |  |  |  |  end: 3:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 3:3
   |  |  |  |  end: 3:8
   |  |  |  position: 3:1
   |  |  |  end: 3:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:9
   position: 1:1
   end: 3:10
