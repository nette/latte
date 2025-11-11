<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {include [file] "file" [with blocks] [,] [params]}
 */
class IncludeFileNode extends StatementNode
{
	public ExpressionNode $file;
	public ArrayNode $args;
	public ModifierNode $modifier;
	public string $mode;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$tag->expectArguments();
		$node = new static;
		$tag->parser->tryConsumeTokenBeforeUnquotedString('file');
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		$node->mode = 'include';

		$stream = $tag->parser->stream;
		if ($stream->tryConsume('with')) {
			$stream->consume('blocks');
			$node->mode = 'includeblock';
		}

		$stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = !$node->modifier->removeFilter('noescape');
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'$this->createTemplate(%raw, %node? + $this->params, %dump)->renderToContentType(%raw) %line;',
			$context->ensureString($this->file, 'Template name'),
			$this->args,
			$this->mode,
			$this->modifier->filters
				? $context->format(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->modifier,
				)
				: PhpHelpers::dump($this->modifier->escape ? $context->getEscaper()->export() : null),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
		yield $this->args;
		yield $this->modifier;
	}
}
