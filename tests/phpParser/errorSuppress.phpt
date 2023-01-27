<?php

// Error suppression

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	@$a,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (1)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ErrorSuppressNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:2 (offset 1)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1 (offset 0)
   position: 1:1 (offset 0)
