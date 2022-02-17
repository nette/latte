<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {debugbreak [$cond]}
 */
class DebugbreakNode extends StatementNode
{
	public ?ExpressionNode $condition;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(null);
		$node = new self;
		$node->condition = $tag->args === '' ? null : $tag->tokenizer;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $compiler->write(
				($this->condition ? 'if (%raw) ' : '') . $func . '() %line;',
				$this->condition?->compile($compiler),
				$this->line,
			);
		}
		return '';
	}


	public function &getIterator(): \Generator
	{
		if ($this->condition) {
			yield $this->condition;
		}
	}
}
