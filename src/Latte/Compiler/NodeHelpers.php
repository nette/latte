<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar;
use function array_merge, constant, defined;


/**
 * Utility functions for working with AST nodes.
 */
final class NodeHelpers
{
	/**
	 * Finds all nodes matching the filter and returns them in traversal order.
	 * @param callable(Node): bool  $filter
	 * @return list<Node>
	 */
	public static function find(Node $node, callable $filter): array
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


	/**
	 * Finds the first node matching the filter, or null if none.
	 * @param callable(Node): bool  $filter
	 */
	public static function findFirst(Node $node, callable $filter): ?Node
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


	/**
	 * Creates a deep clone of the node tree.
	 */
	public static function clone(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, enter: fn(Node $node) => clone $node) ?? throw new \LogicException;
	}


	/**
	 * Evaluates a scalar expression node to a PHP value. Resolves constants when $constants is true.
	 * Throws InvalidArgumentException if the expression cannot be reduced to a value.
	 */
	public static function toValue(ExpressionNode $node, bool $constants = false): mixed
	{
		if ($node instanceof Scalar\BooleanNode
			|| $node instanceof Scalar\FloatNode
			|| $node instanceof Scalar\IntegerNode
			|| $node instanceof Scalar\StringNode
		) {
			return $node->value;

		} elseif ($node instanceof Scalar\NullNode) {
			return null;

		} elseif ($node instanceof Expression\ArrayNode) {
			$res = [];
			foreach ($node->items as $item) {
				$value = self::toValue($item->value, $constants);
				if ($item->key) {
					$key = $item->key instanceof Php\IdentifierNode
						? $item->key->name
						: self::toValue($item->key, $constants);
					$res[$key] = $value;

				} elseif ($item->unpack) {
					$res = array_merge($res, $value);

				} else {
					$res[] = $value;
				}
			}

			return $res;

		} elseif ($node instanceof Expression\ConstantFetchNode && $constants) {
			$name = $node->name->toCodeString();
			return defined($name)
				? constant($name)
				: throw new \InvalidArgumentException("The constant '$name' is not defined.");

		} elseif (
			$node instanceof Expression\ClassConstantFetchNode
			&& $constants
			&& $node->name instanceof Php\IdentifierNode
		) {
			$class = $node->class instanceof Php\NameNode
				? $node->class->toCodeString()
				: self::toValue($node->class, $constants);
			$name = $class . '::' . $node->name->name;
			return defined($name)
				? constant($name)
				: throw new \InvalidArgumentException("The constant '$name' is not defined.");

		} else {
			throw new \InvalidArgumentException('The expression cannot be converted to PHP value.');
		}
	}


	/**
	 * Extracts plain text from a node if it contains only static text, or returns null.
	 */
	public static function toText(?Node $node): ?string
	{
		if ($node instanceof Nodes\FragmentNode) {
			$res = '';
			foreach ($node->children as $child) {
				if (($s = self::toText($child)) === null) {
					return null;
				}
				$res .= $s;
			}

			return $res;
		}

		return match (true) {
			$node instanceof Nodes\TextNode => $node->content,
			$node instanceof Nodes\NopNode => '',
			default => null,
		};
	}
}
