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
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\TagInfo;
use Latte\Helpers;


/**
 * {include [file] "file" [with blocks] [,] [params]}
 */
class IncludeFileNode extends StatementNode
{
	public string $file;
	public ExpressionNode $expression;
	public string $mode;
	public ?ExpressionNode $block = null;
	public bool $noEscape;


	public static function parse(TagInfo $tag): self
	{
		$node = new self;
		$node->expression = $tag->tokenizer;

		[$node->file] = $tag->tokenizer->fetchWordWithModifier('file');
		$node->mode = 'include';
		if ($tag->tokenizer->isNext('with') && !$tag->tokenizer->isPrev(',')) {
			$tag->tokenizer->consumeValue('with');
			$tag->tokenizer->consumeValue('blocks');
			$node->mode = 'includeblock';
		}

		$node->noEscape = Helpers::removeFilter($tag->modifiers, 'noescape');
		if ($tag->modifiers && !$node->noEscape) {
			$tag->modifiers .= '|escape';
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			'$this->createTemplate(%word, %array? + $this->params, %var)->renderToContentType(%raw' . ($this->block ? ', %raw' : '') . ') %5.line;',
			$this->file,
			$this->expression,
			$this->mode,
			$this->expression->modifier
				? $compiler->write(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->expression->modifier,
				)
				: PhpHelpers::dump($this->noEscape ? null : implode('', $compiler->getContext())),
			$this->block ? $compiler->write('%word', $this->block) : null,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
