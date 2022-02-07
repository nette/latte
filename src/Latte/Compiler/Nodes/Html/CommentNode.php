<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;


class CommentNode extends Node
{
	public function __construct(
		public Node $content,
		public ?int $line = null,
	) {
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->setContext(Compiler::CONTEXT_HTML_COMMENT);
		$content = $this->content->compile($compiler);
		$compiler->setContext(Compiler::CONTEXT_HTML_TEXT);
		return "echo '<!--'; $content echo '-->';";
	}
}
