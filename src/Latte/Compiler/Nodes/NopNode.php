<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;


class NopNode extends Node
{
	public function compile(Compiler $compiler): string
	{
		return '';
	}
}
