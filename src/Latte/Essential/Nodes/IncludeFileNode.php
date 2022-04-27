<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\LegacyExprNode;
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
	public LegacyExprNode $args;
	public string $modifier;
	public string $mode;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$node = new static;
		[$node->file] = $tag->tokenizer->fetchWordWithModifier('file');
		$node->mode = 'include';
		if ($tag->tokenizer->isNext('with') && !$tag->tokenizer->isPrev(',')) {
			$tag->tokenizer->consumeValue('with');
			$tag->tokenizer->consumeValue('blocks');
			$node->mode = 'includeblock';
		}

		$node->args = $tag->getArgs();
		$node->modifier = $tag->modifiers;
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
				: PhpHelpers::dump($noEscape ? null : implode('', $context->getEscapingContext())),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
