<?php declare(strict_types=1);

// Literals

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	true,
	false,
	null,

	True,
	False,
	Null,

	TRUE,
	FALSE,
	NULL,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (9)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 1:1
   |  |  |  end: 1:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:5
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 2:1
   |  |  |  end: 2:6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:6
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 3:1
   |  |  |  end: 3:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:5
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 5:1
   |  |  |  end: 5:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:5
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 6:1
   |  |  |  end: 6:6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:6
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 7:1
   |  |  |  end: 7:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:5
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: true
   |  |  |  position: 9:1
   |  |  |  end: 9:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:5
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\BooleanNode
   |  |  |  value: false
   |  |  |  position: 10:1
   |  |  |  end: 10:6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:6
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  position: 11:1
   |  |  |  end: 11:5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 11:5
   position: 1:1
   end: 11:6
