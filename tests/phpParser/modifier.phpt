<?php

// Modifier

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	|truncate: 10, (20|round)|trim
	XX;

$code = normalizeNl($test);
$tokens = (new TagLexer)->tokenize($code);
$parser = new Latte\Compiler\TagParser($tokens);
$node = $parser->parseModifier();
if (!$parser->isEnd()) {
	$parser->stream->throwUnexpectedException();
}

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\ModifierNode
   filters: array (2)
   |  0 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'truncate'
   |  |  |  position: 1:2 (offset 1)
   |  |  args: array (2)
   |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 10
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 1:12 (offset 11)
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:12 (offset 11)
   |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:17 (offset 16)
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  position: 1:20 (offset 19)
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  position: 1:19 (offset 18)
   |  |  |  |  |  position: 1:17 (offset 16)
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:16 (offset 15)
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'trim'
   |  |  |  position: 1:27 (offset 26)
   |  |  args: array (0)
   |  |  position: 1:26 (offset 25)
   escape: false
   check: true
   position: 1:1 (offset 0)
