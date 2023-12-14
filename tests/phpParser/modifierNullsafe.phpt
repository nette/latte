<?php

// Modifier

declare(strict_types=1);

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	?|truncate: 10, (20?|round)?|trim
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
   |  |  |  position: 1:3 (offset 2)
   |  |  args: array (2)
   |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 10
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 1:13 (offset 12)
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:13 (offset 12)
   |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FiltersCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:18 (offset 17)
   |  |  |  |  |  filters: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  position: 1:22 (offset 21)
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  |  position: 1:20 (offset 19)
   |  |  |  |  |  position: 1:17 (offset 16)
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:17 (offset 16)
   |  |  nullsafe: true
   |  |  position: 1:1 (offset 0)
   |  1 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'trim'
   |  |  |  position: 1:30 (offset 29)
   |  |  args: array (0)
   |  |  nullsafe: true
   |  |  position: 1:28 (offset 27)
   escape: false
   check: true
   position: 1:1 (offset 0)
