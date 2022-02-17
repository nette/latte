<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {trace}
 */
class TraceNode extends StatementNode
{
	public static function parse(TagInfo $tag): self
	{
		$tag->validate(false);
		return new self;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			'Latte\Extensions\Tracer::throw() %line;',
			$this->line,
		);
	}
}
