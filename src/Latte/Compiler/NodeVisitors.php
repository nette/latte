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
					return NodeTraverser::StopTraversal;
				}
			});
		return $found;
	}


	public function clone(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, enter: fn(Node $node) => clone $node);
	}
}
