<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;


/**
 * Base for PHP expression nodes (variables, operators, calls, literals).
 */
abstract class ExpressionNode extends Node
{
	/**
	 * Checks whether the expression can be used as the target of an assignment; a ?-> anywhere along
	 * the chain the write reaches through rules it out.
	 */
	public function isWritable(): bool
	{
		return $this->isVariable() && !$this->hasNullsafe();
	}


	/**
	 * Checks whether the expression represents a variable-like access (variable, property, or array element).
	 */
	public function isVariable(): bool
	{
		return $this instanceof Expression\ArrayAccessNode
			|| $this instanceof Expression\PropertyFetchNode
			|| $this instanceof Expression\StaticPropertyFetchNode
			|| $this instanceof Expression\VariableNode;
	}


	/**
	 * Checks whether the expression is a function or method call.
	 */
	public function isCall(): bool
	{
		return $this instanceof Expression\FunctionCallNode
			|| $this instanceof Expression\MethodCallNode
			|| $this instanceof Expression\StaticMethodCallNode;
	}


	/**
	 * Checks whether a ?-> stands anywhere along the chain the expression reads through.
	 */
	protected function hasNullsafe(): bool
	{
		for ($node = $this; $node; $node = $node->reachInto()) {
			if (($node instanceof Expression\PropertyFetchNode || $node instanceof Expression\MethodCallNode) && $node->nullsafe) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Returns the expression this one reads out of; null where it reads out of nothing.
	 */
	private function reachInto(): ?self
	{
		return match (true) {
			$this instanceof Expression\PropertyFetchNode, $this instanceof Expression\MethodCallNode => $this->object,
			$this instanceof Expression\ArrayAccessNode => $this->expr,
			$this instanceof Expression\StaticPropertyFetchNode,
			$this instanceof Expression\StaticMethodCallNode => $this->class instanceof self
				? $this->class
				: null,
			default => null,
		};
	}
}
