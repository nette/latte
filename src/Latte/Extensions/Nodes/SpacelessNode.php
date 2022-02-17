<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;
use Latte\Engine;


/**
 * {spaceless}
 */
class SpacelessNode extends StatementNode
{
	public Node $content;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(false);
		$node = new self;
		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			<<<'XX'
				ob_start('Latte\Extensions\Filters::%raw', 4096) %line;
				try {
					%raw
				} finally {
					ob_end_flush();
				}


				XX,
			$compiler->getContext()[0] === Engine::CONTENT_HTML
				? 'spacelessHtmlHandler'
				: 'spacelessText',
			$this->line,
			$this->content->compile($compiler),
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
