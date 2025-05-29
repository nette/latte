<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;


final class NodeTraverser
{
	public const DontTraverseChildren = 1;
	public const StopTraversal = 2;
	public const RemoveNode = 3;

	/** @var ?callable(Node): (Node|int|null) */
	private $enter;

	/** @var ?callable(Node): (Node|int|null) */
	private $leave;

	private bool $stop;


	public function traverse(Node $node, ?callable $enter = null, ?callable $leave = null): ?Node
	{
		$this->enter = $enter;
		$this->leave = $leave;
		$this->stop = false;
		return $this->traverseNode($node);
	}


	private function traverseNode(Node $node): ?Node
	{
		$children = true;
		if ($this->enter) {
			$res = ($this->enter)($node);
			if ($res instanceof Node) {
				$node = $res;

			} elseif ($res === self::DontTraverseChildren) {
				$children = false;

			} elseif ($res === self::StopTraversal) {
				$this->stop = true;
				return $node;

			} elseif ($res === self::RemoveNode) {
				return null;
			}
		}

		if ($children) {
			foreach ($node as &$subnode) {
				$subnode = $this->traverseNode($subnode);
				if ($this->stop) {
					break;
				}
			}
		}

		if (!$this->stop && $this->leave) {
			$res = ($this->leave)($node);
			if ($res instanceof Node) {
				$node = $res;

			} elseif ($res === self::StopTraversal) {
				$this->stop = true;

			} elseif ($res === self::RemoveNode) {
				return null;
			}
		}

		return $node;
	}
}
