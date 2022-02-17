<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {rollback}
 */
class RollbackNode extends StatementNode
{
	public static function parse(TagInfo $tag): self
	{
		if (!$tag->closest(['try'])) {
			throw new CompileException('Tag {rollback} must be inside {try} ... {/try}.');
		}

		$tag->validate(false);
		return new self;
	}


	public function compile(Compiler $compiler): string
	{
		return 'throw new Latte\Extensions\RollbackException;';
	}
}
