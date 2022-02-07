<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;


final class FragmentNode extends Node
{
	/** @var Node[] */
	public array $children = [];


	/** @param Node[] $children */
	public function __construct(array $children = [])
	{
		foreach ($children as $child) {
			$this->append($child);
		}
	}


	public function append(Node $node): static
	{
		if ($node instanceof self) {
			$this->children = array_merge($this->children, $node->children);
		} elseif (!$node instanceof NopNode) {
			$this->children[] = $node;
		}
		$this->line ??= $node->line;
		return $this;
	}


	public function compile(Compiler $compiler): string
	{
		$res = '';
		foreach ($this->children as $child) {
			$res .= $child->compile($compiler);
		}

		return $res;
	}
}
