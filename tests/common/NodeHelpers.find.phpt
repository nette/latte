<?php declare(strict_types=1);

use Latte\Compiler\{Node, NodeHelpers};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/nodehelpers.php';

$leafNode1 = new LeafNode;
$leafNode2 = new LeafNode;
$parentNode = new ParentNode($leafNode2);
$arrayNode = new ArrayNode([$leafNode1, $parentNode]);

$res = NodeHelpers::find($arrayNode, fn(Node $node) => $node instanceof LeafNode);
Assert::same([$leafNode1, $leafNode2], $res);

$res = NodeHelpers::findFirst($arrayNode, fn(Node $node) => $node instanceof LeafNode);
Assert::same($leafNode1, $res);

// a node class works as the filter
$res = NodeHelpers::find($arrayNode, LeafNode::class);
Assert::same([$leafNode1, $leafNode2], $res);

$res = NodeHelpers::findFirst($arrayNode, LeafNode::class);
Assert::same($leafNode1, $res);

Assert::exception(
	fn() => NodeHelpers::find($arrayNode, stdClass::class),
	LogicException::class,
	'Filter must be a Latte\Compiler\Node subclass, stdClass given.',
);
