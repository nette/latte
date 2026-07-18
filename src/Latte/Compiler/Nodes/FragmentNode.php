<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;
use Latte\Helpers;
use function array_merge, count;


/**
 * Container for sequence of child nodes.
 */
final class FragmentNode extends AreaNode
{
	/** @var AreaNode[] */
	public array $children = [];


	/** @param AreaNode[]  $children */
	public function __construct(array $children = [])
	{
		foreach ($children as $child) {
			$this->append($child);
		}
	}


	/**
	 * Appends a node. FragmentNode children are merged inline; NopNodes are discarded.
	 */
	public function append(AreaNode $node): static
	{
		if ($node instanceof self) {
			$this->children = array_merge($this->children, $node->children);
		} elseif (!$node instanceof NopNode) {
			$this->children[] = $node;
		}
		return $this;
	}


	/**
	 * Recomputes the extent to span the children (lowest start, highest end). Called by the
	 * parser once the tree is built; a fragment has no extent of its own until then.
	 */
	public function updateExtent(): void
	{
		$this->position = $this->end = null;
		foreach ($this->children as $child) {
			if ($child->position && (!$this->position || $child->position->offset < $this->position->offset)) {
				$this->position = $child->position;
			}
			if ($child->end && (!$this->end || $child->end->offset > $this->end->offset)) {
				$this->end = $child->end;
			}
		}
	}


	/**
	 * Returns null (or self) for an empty fragment, the single child when there is only one, or self otherwise.
	 */
	public function simplify(bool $allowsNull = true): ?AreaNode
	{
		return match (true) {
			!$this->children => $allowsNull ? null : $this,
			count($this->children) === 1 => $this->children[0],
			default => $this,
		};
	}


	public function print(PrintContext $context): string
	{
		$res = '';
		foreach ($this->children as $child) {
			$res .= $child->print($context);
		}

		return $res;
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->children as &$item) {
			yield $item;
		}
		Helpers::removeNulls($this->children);
	}
}
