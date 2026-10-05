<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use function is_array;


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
	 * Returns the value the expression is written as: a scalar, null, or an array of them; resolves
	 * constants when $constants is true.
	 * @throws \InvalidArgumentException  where the expression is written as no value, as hasValue() tells beforehand
	 */
	public function toValue(bool $constants = false): mixed
	{
		$value = $this->readValue($constants)
			?? throw new \InvalidArgumentException('The expression cannot be converted to PHP value.');
		return $value[0];
	}


	/**
	 * Checks whether the expression is written as a value, which is what toValue() returns.
	 */
	public function hasValue(bool $constants = false): bool
	{
		return $this->readValue($constants) !== null;
	}


	/** @return ?array{mixed}  the value in a list, so that null tells no value apart from the value null */
	private function readValue(bool $constants): ?array
	{
		if ($this instanceof Scalar\BooleanNode
			|| $this instanceof Scalar\FloatNode
			|| $this instanceof Scalar\IntegerNode
			|| $this instanceof Scalar\StringNode
		) {
			return [$this->value];

		} elseif ($this instanceof Scalar\NullNode) {
			return [null];

		} elseif ($this instanceof Expression\ArrayNode) {
			$res = [];
			foreach ($this->items as $item) {
				$value = $item->value->readValue($constants);
				if ($value === null) {
					return null;

				} elseif ($item->key) {
					$key = $item->key instanceof IdentifierNode
						? [$item->key->name]
						: $item->key->readValue($constants);
					if ($key === null) {
						return null;
					}
					$res[$key[0]] = $value[0];

				} elseif ($item->unpack) {
					if (!is_array($value[0])) {
						return null;
					}
					$res = [...$res, ...$value[0]];

				} else {
					$res[] = $value[0];
				}
			}

			return [$res];

		} elseif ($this instanceof Expression\ConstantFetchNode && $constants) {
			$name = $this->name->toCodeString();
			return defined($name) ? [constant($name)] : null;

		} elseif (
			$this instanceof Expression\ClassConstantFetchNode
			&& $constants
			&& $this->name instanceof IdentifierNode
		) {
			$class = $this->class instanceof NameNode
				? [$this->class->toCodeString()]
				: $this->class->readValue($constants);
			$name = $class === null ? null : $class[0] . '::' . $this->name->name;
			return $name !== null && defined($name) ? [constant($name)] : null;
		}

		return null;
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
