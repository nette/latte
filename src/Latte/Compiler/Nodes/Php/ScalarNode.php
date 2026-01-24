<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;


/**
 * Base for literal value nodes (strings, numbers, booleans, null).
 */
abstract class ScalarNode extends ExpressionNode
{
	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
