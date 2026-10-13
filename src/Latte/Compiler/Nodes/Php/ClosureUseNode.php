<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\{Node, Position, PrintContext};
use Latte\Compiler\Nodes\Php\Expression\VariableNode;


/**
 * Closure use variable ($var or &$var).
 */
class ClosureUseNode extends Node
{
	public function __construct(
		public VariableNode $var,
		public bool $byRef = false,
		public ?Position $position = null,
		public ?Position $end = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->byRef ? '&' : '') . $this->var->print($context);
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
	}
}
