<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {do $statement}
 */
class DoNode extends StatementNode
{
	public Node $statement;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(true);
		$node = new self;
		$node->statement = $tag->tokenizer;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			'%raw %line;',
			$this->statement->compile($compiler),
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->statement;
	}
}
