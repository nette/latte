<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;


/**
 * HTML bogus tag.
 */
class BogusTagNode extends Node
{
	public function __construct(
		public string $openDelimiter,
		public Node $content,
		public string $endDelimiter,
		public ?int $line = null,
	) {
	}


	public function compile(Compiler $compiler): string
	{
		$res = 'echo ' . var_export($this->openDelimiter, true) . ';';
		$compiler->setContext(Compiler::CONTEXT_HTML_BOGUS_COMMENT);
		$res .= $this->content->compile($compiler);
		$compiler->setContext(Compiler::CONTEXT_HTML_TEXT);
		$res .= 'echo ' . var_export($this->endDelimiter, true) . ';';
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
