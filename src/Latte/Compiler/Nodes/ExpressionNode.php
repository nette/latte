<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


/** Temporary expression node */
class ExpressionNode extends Node
{
	public function __construct(
		public string $text,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->format('%args', $this);
	}
}
