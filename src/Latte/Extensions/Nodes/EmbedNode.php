<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Parser;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\TagInfo;


/**
 * {embed [block|file] name [,] [params]}
 */
class EmbedNode extends StatementNode
{
	public string $name;
	public string $mode;
	public ExpressionNode $tag;
	public Node $blocks;
	public int|string|null $layer;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(true);

		$node = new self;
		[$node->name, $mode] = $tag->tokenizer->fetchWordWithModifier(['block', 'file']);
		$node->mode = $mode ?? (preg_match('~^[\w-]+$~DA', $node->name) ? 'block' : 'file');
		$node->tag = $tag->tokenizer;

		$prevIndex = $parser->layer;
		$parser->layer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->layer] = [];
		[$node->blocks] = $tag->empty
			? [new FragmentNode]
			: yield;

		foreach ($node->blocks->children as $child) {
			if (!$child instanceof ImportNode && !$child instanceof BlockNode && !$child instanceof TextNode) {
				throw new CompileException('Unexpected content inside {embed} tags.');
			}
		}

		$parser->layer = $prevIndex;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$imports = '';
		foreach ($this->blocks->children as $child) {
			if ($child instanceof ImportNode) {
				$imports .= $child->compile($compiler);
			} else {
				$child->compile($compiler);
			}
		}

		return $this->mode === 'file'
			? $compiler->write(
				<<<'XX'
					$this->enterBlockLayer(%var, get_defined_vars()) %line; %raw
					try {
						$this->createTemplate(%word, %array, "embed")->renderToContentType(%var) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->line,
				$imports,
				$this->name,
				$this->tag,
				implode('', $compiler->getContext()),
			)
			: $compiler->write(
				<<<'XX'
					$this->enterBlockLayer(%var, get_defined_vars()) %line; %raw
					$this->copyBlockLayer();
					try {
						$this->renderBlock(%raw, %array, %var) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->line,
				$imports,
				Block::isDynamic($this->name) ? $compiler->write('%word', $this->name) : PhpHelpers::dump($this->name),
				$this->tag,
				implode('', $compiler->getContext()),
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->blocks;
	}
}
