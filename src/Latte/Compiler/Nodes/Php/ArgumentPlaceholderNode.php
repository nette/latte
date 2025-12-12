<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ArgumentPlaceholderNode extends Node
{
	public function __construct(
		public ?IdentifierNode $name = null,
		public ?Position $position = null,
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
