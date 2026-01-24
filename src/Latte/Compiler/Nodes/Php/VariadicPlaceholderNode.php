<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


/**
 * Placeholder ... for partial function application or first-class callable creation.
 */
class VariadicPlaceholderNode extends Node
{
	public function __construct(
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return '...';
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
