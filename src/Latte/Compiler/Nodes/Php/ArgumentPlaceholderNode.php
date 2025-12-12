<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\{Node, Position, PrintContext};


/**
 * Placeholder ? for partial function application, optionally named.
 */
class ArgumentPlaceholderNode extends Node
{
	public function __construct(
		public ?IdentifierNode $name = null,
		public ?Position $position = null,
		public ?Position $end = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->name ? $this->name . ': ' : '') . '?';
	}


	public function &getIterator(): \Generator
	{
		if ($this->name) {
			yield $this->name;
		}
	}
}
