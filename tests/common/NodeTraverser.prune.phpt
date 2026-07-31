<?php declare(strict_types=1);

use Latte\Compiler\{Node, NodeTraverser};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/nodehelpers.php';


test('RemoveNode leaves no hole', function () {
	$node = new ArrayNode([new LeafNode, new LeafNode, new LeafNode]);
	$first = $node->items[0];

	(new NodeTraverser)->traverse($node, fn(Node $n) => $n === $first ? NodeTraverser::RemoveNode : null);

	Assert::count(2, $node->items);
	Assert::notContains(null, $node->items);
});


test('StopTraversal right after RemoveNode leaves no hole', function () {
	$node = new ArrayNode([new LeafNode, new LeafNode, new LeafNode]);
	[$first, $second] = $node->items;

	(new NodeTraverser)->traverse($node, function (Node $n) use ($first, $second) {
		if ($n === $first) {
			return NodeTraverser::RemoveNode;
		}
		if ($n === $second) {
			return NodeTraverser::StopTraversal;
		}
		return null;
	});

	Assert::count(2, $node->items);
	Assert::notContains(null, $node->items);
});


test('an exception mid-traversal leaves no hole', function () {
	$node = new ArrayNode([new LeafNode, new LeafNode, new LeafNode]);
	[$first, $second] = $node->items;

	Assert::exception(
		fn() => (new NodeTraverser)->traverse($node, function (Node $n) use ($first, $second) {
			if ($n === $first) {
				return NodeTraverser::RemoveNode;
			}
			if ($n === $second) {
				throw new RuntimeException;
			}
			return null;
		}),
		RuntimeException::class,
	);

	Assert::notContains(null, $node->items);
});
