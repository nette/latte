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
 * {dump [$var]}
 */
class DumpNode extends StatementNode
{
	public ?ExpressionNode $expression = null;
	public ?string $description = null;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(null);
		$node = new self;
		if ($tag->args !== '') {
			$node->expression = $tag->tokenizer;
			$node->description = $tag->args;
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			$this->expression
				? 'Tracy\Debugger::barDump((%raw), %var) %line;'
				: 'Tracy\Debugger::barDump(get_defined_vars(), \'variables\') %2.line;',
			$this->expression?->compile($compiler),
			$this->description,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->expression) {
			yield $this->expression;
		}
	}
}
