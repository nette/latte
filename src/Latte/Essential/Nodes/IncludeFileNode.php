<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Helpers;


/**
 * {include [file] "file" [with blocks] [,] [params]}
 */
class IncludeFileNode extends StatementNode
{
	public string $file;
	public ExpressionNode $args;
	public string $modifier;
	public string $mode;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$node = new static;
		[$node->file] = $tag->parser->fetchWordWithModifier('file');
		$node->mode = 'include';
		if ($tag->parser->isNext('with') && !$tag->parser->isPrev(',')) {
			$tag->parser->consumeValue('with');
			$tag->parser->consumeValue('blocks');
			$node->mode = 'includeblock';
		}

		$node->args = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->modifiers;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$modifier = $this->modifier;
		$noEscape = Helpers::removeFilter($modifier, 'noescape');
		if ($modifier && !$noEscape) {
			$modifier .= '|escape';
		}

		return $context->format(
			'$this->createTemplate(%word, %array? + $this->params, %dump)->renderToContentType(%raw) %line;',
			$this->file,
			$this->args,
			$this->mode,
			$modifier
				? $context->format(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$modifier,
				)
				: PhpHelpers::dump($noEscape ? null : $context->getEscaper()->export()),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
