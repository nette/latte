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
 * {templateType ClassName}
 */
class TemplateTypeNode extends StatementNode
{
	public bool $allowedInHead = true;


	public static function parse(TagInfo $tag): self
	{
		if (!$tag->isInHead()) {
			throw new CompileException('{templateType} is allowed only in template header.');
		}
		$tag->validate('class name');
		return new self;
	}


	public function compile(Compiler $compiler): string
	{
		return '';
	}
}
