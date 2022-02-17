<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Block;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\CallableNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * {snippetArea [name]}
 */
class SnippetAreaNode extends StatementNode
{
	public Block $block;
	public Node $content;
	public TagInfo $tag;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(null);
		$name = (string) $tag->tokenizer->fetchWord();
		$tag->checkExtraArgs();
		$node = new self;
		$node->tag = $tag;
		$node->block = $parser->addBlock($name, Template::LAYER_SNIPPET, 'snippet');
		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->addBlock($this->block, new CallableNode(fn() => $compiler->write(
			<<<'XX'
				$this->global->snippetDriver->enter(%var, %var);
				try {
					%raw
				} finally {
					$this->global->snippetDriver->leave();
				}

				XX,
			$this->block->name,
			SnippetDriver::TYPE_AREA,
			$this->content->compile($compiler),
		)), $this->tag);

		return $compiler->write(
			'$this->renderBlock(%var, [], null, %var) %line;',
			$this->block->name,
			Template::LAYER_SNIPPET,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
