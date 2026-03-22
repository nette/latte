<?php declare(strict_types=1);

// First-class callables

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo(...),
	$this->foo(...),
	A::foo(...),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'foo'
   |  |  |  |  kind: 1
   |  |  |  |  position: 1:1+3
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 1:5+3
   |  |  |  position: 1:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+8
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'this'
   |  |  |  |  position: 2:1+5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 2:8+3
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 2:12+3
   |  |  |  nullsafe: false
   |  |  |  position: 2:1+15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+15
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1+1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 3:4+3
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 3:8+3
   |  |  |  position: 3:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+11
   position: 1:1+39
