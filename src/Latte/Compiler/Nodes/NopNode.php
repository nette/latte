<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


/**
 * Placeholder for removed or filtered-out content.
 */
class NopNode extends AreaNode
{
	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
