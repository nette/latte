<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$node = new Nodes\AuxiliaryNode(
	fn(PrintContext $context, $a, $b, $c) => $context->format('%node %node %node', $a, $b, $c),
	new StringNode('a'),
	null,
	new StringNode('b'),
);

$node = (new NodeTraverser)->traverse(
	$node,
	fn(Node $node) => $node instanceof StringNode ? new StringNode('new') : $node
);
Assert::same("'new' 'new'", $node->print(new PrintContext));


$node = new Expression\AuxiliaryNode(
	fn(PrintContext $context, $a, $b, $c) => $context->format('%node %node %node', $a, $b, $c),
	new StringNode('a'),
	null,
	new StringNode('b'),
);

$node = (new NodeTraverser)->traverse(
	$node,
	fn(Node $node) => $node instanceof StringNode ? new StringNode('new') : $node
);
Assert::same("'new' 'new'", $node->print(new PrintContext));
