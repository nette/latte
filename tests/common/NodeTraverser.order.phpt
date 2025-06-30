<?php

declare(strict_types=1);

use Latte\Compiler\NodeTraverser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/nodehelpers.php';

$leafNode1 = new LeafNode;
$leafNode2 = new LeafNode;
$parentNode = new ParentNode($leafNode2);
$arrayNode = new ArrayNode([$leafNode1, $parentNode]);

$tracer = new TracingVisitor;
$res = (new NodeTraverser)->traverse(
	$arrayNode,
	[$tracer, 'enter'],
	[$tracer, 'leave'],
);

Assert::equal($arrayNode, $res);
Assert::equal([
	['enter', $arrayNode],
	['enter', $leafNode1],
	['leave', $leafNode1],
	['enter', $parentNode],
	['enter', $leafNode2],
	['leave', $leafNode2],
	['leave', $parentNode],
	['leave', $arrayNode],
], $tracer->trace);



$trace = [];
$res = (new NodeTraverser)->traverse(
	$arrayNode,
	function ($node) use (&$trace) {
		$trace[] = ['enter', $node];
		return $node instanceof ParentNode
			? NodeTraverser::DontTraverseChildren
			: null;
	},
	function ($node) use (&$trace) { $trace[] = ['leave', $node]; },
);

Assert::equal($arrayNode, $res);
Assert::equal([
	['enter', $arrayNode],
	['enter', $leafNode1],
	['leave', $leafNode1],
	['enter', $parentNode],
	['leave', $parentNode],
	['leave', $arrayNode],
], $trace);



$trace = [];
$res = (new NodeTraverser)->traverse(
	$arrayNode,
	function ($node) use (&$trace) {
		$trace[] = ['enter', $node];
		return $node instanceof ParentNode
			? NodeTraverser::StopTraversal
			: null;
	},
	function ($node) use (&$trace) { $trace[] = ['leave', $node]; },
);

Assert::equal($arrayNode, $res);
Assert::equal([
	['enter', $arrayNode],
	['enter', $leafNode1],
	['leave', $leafNode1],
	['enter', $parentNode],
], $trace);



$trace = [];
$res = (new NodeTraverser)->traverse(
	$arrayNode,
	function ($node) use (&$trace) { $trace[] = ['enter', $node]; },
	function ($node) use (&$trace) {
		$trace[] = ['leave', $node];
		return $node instanceof ParentNode
			? NodeTraverser::StopTraversal
			: null;
	},
);

Assert::equal($arrayNode, $res);
Assert::equal([
	['enter', $arrayNode],
	['enter', $leafNode1],
	['leave', $leafNode1],
	['enter', $parentNode],
	['enter', $leafNode2],
	['leave', $leafNode2],
	['leave', $parentNode],
], $trace);



// Test RemoveNode functionality
$leafNode3 = new LeafNode;
$leafNode4 = new LeafNode;
$arrayNodeForRemoval = new ArrayNode([$leafNode3, $leafNode4]);

$trace = [];
$res = (new NodeTraverser)->traverse(
	$arrayNodeForRemoval,
	function ($node) use (&$trace, $leafNode4) {
		$trace[] = ['enter', $node];
		return $node === $leafNode4
			? NodeTraverser::RemoveNode
			: null;
	},
	function ($node) use (&$trace) { $trace[] = ['leave', $node]; },
);

Assert::equal($arrayNodeForRemoval, $res);
Assert::equal([
	['enter', $arrayNodeForRemoval],
	['enter', $leafNode3],
	['leave', $leafNode3],
	['enter', $leafNode4],
	['leave', $arrayNodeForRemoval],
], $trace);

// Verify that the removed node is replaced with null in the array
$children = [];
foreach ($arrayNodeForRemoval as $child) {
	$children[] = $child;
}
Assert::equal([$leafNode3], $children);
