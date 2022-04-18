<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;


abstract class ExpressionNode extends Node
{
	public static function fromValue(self|bool|int|float|string|array|null $value): self
	{
		return match (true) {
			$value === null => new Scalar\NullNode,
			is_bool($value) => new Scalar\BooleanNode($value),
			is_int($value) => new Scalar\IntegerNode($value),
			is_float($value) => new Scalar\FloatNode($value),
			is_string($value) => new Scalar\StringNode($value),
			is_array($value) => Expression\ArrayNode::fromArray($value),
			$value instanceof self => $value,
		};
	}
}
