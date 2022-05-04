<?php

/**
 * Test: Latte\Compiler::expandMacro() and reentrant.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
$set = new Latte\Macros\MacroSet($compiler);
$set->addMacro('test', 'echo %node.word', 'echo %node.word');

$node = $compiler->expandMacro('test', 'first second', '');
Assert::same('<?php echo "first" ?>', $node->openingCode);
$node->macro->nodeClosed($node);
Assert::same('<?php echo "first" ?>', $node->closingCode);
