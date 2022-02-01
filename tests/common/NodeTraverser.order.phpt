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
