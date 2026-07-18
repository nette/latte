<?php declare(strict_types=1);

// In operators

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a in $b,

	/* precedence */
	$a in $b || $c in $d,
	$a = $b in $c,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\InNode
   |  |  |  needle: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:1
   |  |  |  |  end: 1:3
   |  |  |  haystack: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:7
   |  |  |  |  end: 1:9
   |  |  |  position: 1:1
   |  |  |  end: 1:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:9
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\InNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 4:1
   |  |  |  |  |  end: 4:3
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 4:7
   |  |  |  |  |  end: 4:9
   |  |  |  |  position: 4:1
   |  |  |  |  end: 4:9
   |  |  |  operator: '||'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\InNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 4:13
   |  |  |  |  |  end: 4:15
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  position: 4:19
   |  |  |  |  |  end: 4:21
   |  |  |  |  position: 4:13
   |  |  |  |  end: 4:21
   |  |  |  position: 4:1
   |  |  |  end: 4:21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:21
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:1
   |  |  |  |  end: 5:3
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\InNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 5:6
   |  |  |  |  |  end: 5:8
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 5:12
   |  |  |  |  |  end: 5:14
   |  |  |  |  position: 5:6
   |  |  |  |  end: 5:14
   |  |  |  byRef: false
   |  |  |  position: 5:1
   |  |  |  end: 5:14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:14
   position: 1:1
   end: 5:15
