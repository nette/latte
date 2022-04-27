<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Context;
use Latte\Helpers;


/**
 * {= ...}
 */
class PrintNode extends StatementNode
{
	public LegacyExprNode $expression;
	public string $modifier;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputKeepIndentation;

		$stream = $parser->getStream();
		if (
			$tag->isInText()
			&& $parser->getContentType() === Context::Html
			&& $tag->htmlElement?->name === 'script'
			&& ($token = $stream->peek(-2))
			&& preg_match('#["\']$#D', $token->text)
		) {
			throw new CompileException("Do not place {$tag->getNotation(true)} inside quotes in JavaScript.", $tag->position);
		}

		$tag->extractModifier();
		$tag->expectArguments();
		$node = new static;
		$node->expression = $tag->getArgs();
		$node->modifier = $tag->modifiers;
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
			"echo %modify(%raw) %line;\n",
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
