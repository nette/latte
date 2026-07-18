<?php declare(strict_types=1);

// Interpolated string negative var offsets

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"$a[-0]",
	"$a[-1]",
	"$a[-0x0]",
	"$a[-00]",
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
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:2
   |  |  |  |  |  |  end: 1:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-0'
   |  |  |  |  |  |  position: 1:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 1:2
   |  |  |  |  |  end: 1:8
   |  |  |  position: 1:1
   |  |  |  end: 1:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:9
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:2
   |  |  |  |  |  |  end: 2:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: -1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 2:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 2:2
   |  |  |  |  |  end: 2:8
   |  |  |  position: 2:1
   |  |  |  end: 2:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:9
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:2
   |  |  |  |  |  |  end: 3:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-0x0'
   |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 3:2
   |  |  |  |  |  end: 3:10
   |  |  |  position: 3:1
   |  |  |  end: 3:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:11
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:2
   |  |  |  |  |  |  end: 4:4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-00'
   |  |  |  |  |  |  position: 4:5
   |  |  |  |  |  |  end: null
   |  |  |  |  |  position: 4:2
   |  |  |  |  |  end: 4:9
   |  |  |  position: 4:1
   |  |  |  end: 4:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:10
   position: 1:1
   end: 4:11
