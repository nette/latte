<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Node;


abstract class StatementNode extends Node // TODO: rename
{
	public bool $replaced = false;
	public bool $allowedInHead = false;
}
