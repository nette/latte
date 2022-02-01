<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;


final class NodeVisitors
{
	/** @return Node[] */
	public function find(Node $node, callable $filter): array
	{
		$found = [];
		(new NodeTraverser)
			->traverse($node, enter: function (Node $node) use ($filter, &$found) {
				if ($filter($node)) {
					$found[] = $node;
				}
			});
		return $found;
	}


	public function findFirst(Node $node, callable $filter): ?Node
	{
		$found = null;
		(new NodeTraverser)
			->traverse($node, enter: function (Node $node) use ($filter, &$found) {
				if ($filter($node)) {
					$found = $node;
					return NodeTraverser::STOP_TRAVERSAL;
				}
			});
		return $found;
	}


	public function clone(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, enter: fn(Node $node) => clone $node);
	}


	public function optimizeTree(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, leave: \Closure::fromCallable([$this, 'optimizeVisitor']));
	}


	private function optimizeVisitor(Node $node): ?Node
	{
		if ($node::class !== Nodes\FragmentNode::class) {
			return null;
		}

		$res = [];
		$last = null;
		foreach ($node->children as $child) {
			if ($child instanceof Nodes\NopNode) {
				// nothing
			} elseif ($child instanceof Nodes\TextNode && $last instanceof Nodes\TextNode) {
				$last->content .= $child->content;
			} else {
				$res[] = $last = $child;
			}
		}

		if (!$res) {
			return new Nodes\NopNode;

		} elseif (count($res) === 1) {
			return $res[0];

		} else {
			$node->children = $res;
			return null;
		}
	}
}
