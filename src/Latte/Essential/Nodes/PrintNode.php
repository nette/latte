<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\ContentType;
use Latte\Helpers;


/**
 * {= ...}
 */
class PrintNode extends StatementNode
{
	public ExpressionNode $expression;
	public string $modifier;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputKeepIndentation;

		$stream = $parser->getStream();
		if (
			$tag->isInText()
			&& $parser->getContentType() === ContentType::Html
			&& $tag->htmlElement?->name === 'script'
			&& preg_match('#["\']#A', $stream->peek()->text)
		) {
			throw new CompileException("Do not place {$tag->getNotation(true)} inside quotes in JavaScript.", $tag->position);
		}

		$tag->extractModifier();
		$tag->expectArguments();
		$node = new static;
		$node->expression = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->modifiers;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$modifier = $this->modifier;
		if (Helpers::removeFilter($modifier, 'noescape')) {
			$context->checkFilterIsAllowed('noescape');
		} else {
			$modifier .= '|escape';
		}

		return $context->format(
			"echo %modify(%node) %line;\n",
			$modifier,
			$this->expression,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
